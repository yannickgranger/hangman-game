# Hexagon build with BDD / TDD 

When starting to build Hexagon, can use BDD to ensures business rules are implemented
and TDD helps writing correct implementation

Here is a typical workflow:

- Based on the BDD scenarios, write failing tests (one test per scenario) in the tests/HangmanTest.php file.
- Implement the minimal code required in the game.php file to make the failing tests pass.
- Focus on building the functionality step-by-step based on the tests.
- Once a test passes, refactor and improve the code for maintainability and readability without breaking the tests.
- Repeat for All Scenarios
- Continue iterating through BDD scenarios, writing failing tests, and implementing code to make them pass.
This ensures continuous testing and development guided by user stories.
  The Ports and Adapters pattern (also known as Hexagonal Architecture or Clean Architecture) promotes separation of concerns by isolating your application's core domain logic from external dependencies like databases, messaging systems, or user interfaces. This approach results in cleaner, more maintainable, and testable code.

## Workflow to create domain:

### Define Domain Interfaces:

- Identify core functionalities within your domain.
- Create interfaces in src/Domain to represent these functionalities (e.g., WordRepositoryInterface, GameServiceInterface).
  These interfaces define the contracts for interacting with your domain logic.
- Implement domain entities,
- find / implement value objects,
- write specific exceptions in src/Domain. These are business exceptions

  These classes represent the core concepts and rules within your application (e.g., Word, Game, InvalidGuessException).
  Domain logic should only depend on other domain concepts and interfaces.
