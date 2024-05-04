# Recall the test pyramid

## Presentation Tests (UI/UX Tests): aka E2E tests
These tests specifically focus on the user interface or user experience.
They might involve simulating user interactions (clicks, inputs) and verifying the resulting visual output or user flow.

- in game context:
    Simulating user input for a guess and verifying the command displays the updated word representation correctly.
    Testing how error messages are displayed for invalid user input.

## Integration Tests:
These tests verify how multiple components or modules within a system interact and function together.
While they might involve interacting with databases, external services, or other internal modules, they don't typically test how information is presented to the user.

- in game context:
    Testing how PlayGameUseCase interacts with the HangmanGame object to process a guess (might involve mocking user input/output).
    Testing how the game interacts with a mock storage layer (if used) to save/load game data.

## Unit Tests:
These tests focus on the smallest testable units of code (e.g., functions, methods) in isolation.
They don't involve any presentation layer aspects.

- in game context:
    Testing logic within the Letter class (e.g., validating letter characters).
    Testing methods in HangmanGame that don't rely on external interactions (e.g., checking if the word contains a guessed letter).

# Why a pyramid ?

    The base of the pyramid is the widest, containing numerous unit tests for code isolation.
    Integration tests are fewer but ensure crucial functionalities work together.
    Presentation tests are the least numerous and focus on user interaction and experience.

    The higher the level of test, the highest cost to develop and maintain the tests
    In general developpers focus on using test to help them code implementation
    This is a current mistake: your focus should be testing business (domain), implem is important but tests
    will break at each change in implem details linked to infrastructure ... you don't want to rewrite
    tests each time you change code in infra !!