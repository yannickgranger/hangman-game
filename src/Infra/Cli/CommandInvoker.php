<?php

declare(strict_types=1);

namespace App\Infra\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class CommandInvoker
{
    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function run(Command $command, array $input, array $output)
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'debug:twig',
            // (optional) define the value of command arguments
            'fooArgument' => 'barValue',
            // (optional) pass options to the command
            '--bar' => 'fooValue',
            // (optional) pass options without value
            '--baz' => true,
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();
    }
}