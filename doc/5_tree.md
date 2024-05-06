# Directory tree

```
src
├── Domain
│   ├── Entity
│   │   ├── HangmanGame.php
│   │   └── Word.php
│   ├── Exception
│   │   └── InvalidGuessException.php
│   ├── Repository
│   │   ├── GameRepositoryInterface.php
│   │   └── WordRepositoryInterface.php
│   ├── Service
│   │   └── GameServiceInterface.php
│   ├── UseCase
│   │   └── PlayGameUseCase.php
│   └── ValueObject
│       └── Letter.php
├── Infra
│   └── Persistence
│       └── Repository
│           ├── GameRedisRepository.php
│           ├── WordInMemoryRepository.php
│           └── WordPostgresRepository.php
├── Kernel.php
└── UI
    ├── CLI
    │   └── PlayGameCommand.php
    └── Http
        ├── CreateGameAction.php
        ├── CreateGameActionResponder.php
        ├── GuessLetterAction.php
        └── GuessLetterResponder.php
```

Sometimes there's an "app" direcotry under src/
The app directory in a well-structured application using the ADR pattern
is generally not the ideal place to implement core application logic.

Purpose of app Directory in that case:

The app directory (if present) often serves as a catch-all for application-specific code
that doesn't fit cleanly into other layers. It's not strictly necessary in every application,
especially for smaller projects.

It may contain, for example:

- Application-wide Configuration (if not handled in a config/ dir)
- Event Listeners: Classes that listen for and react to application events (if not handled by dedicated services).
- Custom Console Commands: Custom commands specific to your application that don't fit well into other layers.
- CQRS command, query and handlers