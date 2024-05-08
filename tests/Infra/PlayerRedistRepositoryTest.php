<?php

namespace App\Tests\Infra;

use App\Domain\Entity\Player;
use App\Domain\Repository\PlayerRepositoryInterface;
use App\Infra\Persistence\Repository\PlayerRedisRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class PlayerRedistRepositoryTest extends KernelTestCase
{
    private PlayerRepositoryInterface $playerRepository;
    private ?Uuid $id = null;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();
        $client = new \Redis();
        $client->connect('redis', 6379);
        $this->playerRepository = new PlayerRedisRepository($client, $container->get(SerializerInterface::class));
        $this->playerRepository->flushAll();
        $id = Uuid::v4();
        $player = new Player($id, 'bob');
        $this->playerRepository->save($id, $player);
        $this->id = $id;
        self::assertTrue($this->playerRepository->getUserByUsername('bob') instanceof Player);
    }

    public function testItCanNotCreateSameUser()
    {
        $player = new Player($this->id, 'bob');
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Username already exists: bob');
        $this->playerRepository->save($this->id, $player);
    }

    public function testItCreatesNewUser()
    {
        $player = new Player($this->id, 'alice');
        $this->playerRepository->save(Uuid::v4(), $player);
        $player = $this->playerRepository->getUserByUsername('alice');
        self::assertTrue($player instanceof Player);
    }

    public function testIfUserExists()
    {
        $player = $this->playerRepository->getUserByUsername('bob');
        self::assertTrue($player instanceof Player);
    }

    public function testItDeletesUser()
    {
        $id = $this->id;
        self::assertTrue($this->playerRepository->getUserById($id) instanceof Player);
        self::assertTrue($this->playerRepository->getUserByUsername('bob') instanceof Player);
        $this->playerRepository->delete($id);
        self::assertTrue($this->playerRepository->getUserById($id) === null);
        self::assertTrue($this->playerRepository->getUserByUsername('bob') === null);
    }
}
