Sometimes Clean archi / Hexagonal archi is also called Progressive archi.
Why ?

# From Hexagon to CLI to Api

When building domain logic first, you can add layers progressively, and
complexity is also progressive.

### Hexagon:
Start from business rules and use tests to build the core domain.

### Cli:
In CLI context, more than a simple domain and its rules we have to interact
with this domain to provide input / output of the command line.

That's why we introduced _PlayGameUseCase_ class, with WordRepositoryInterface.
Use case helps also to document interactions with domain, and adding some tests.

### API / HTTP

In an API context, Http interactions with PHP requires a persistence layer,
because PHP does not keep variables in memory between requests.

Here we have more complexity to make orchestration of infrastructure ports and usage of domain.
That's important to separate concerns, and some patterns may help to avoid coupling.

- ADR (Action Domain Responder)
- CQS / CQRS (for a domain with long lasting calculations and async requirements]

In the case of ADR it's possible to either use injection of a service interface
that implements domain interactions, or directly use domain entities,
(use "new Game()" when creating a game).

Note that the GameServiceInterface, when placed in domain/service, is defined
as a domain service, so it will encapsulate reusable game logic that operates on domain entities.
They don't directly interact with external layers like databases or user interfaces. So a domain
service can not use ports. The domain service access and manipulates domain objects and avoid using
repositories. If it needs absolutely to access Ports, inject the RepositoryInterface.

For persistence I choosed a simple redis store, but for future evolutions,
like statistics, players login, rewards, we will have to use a SQL database.
And finally, to use redis we add a docker environment.