# Hangman with BDD/TDD and Docker: A Developer's Playground
This project demonstrates the development of a Hangman game using Behavior-Driven Development (BDD) and Test-Driven Development (TDD) within a Dockerized environment.

## Getting Started
This project assumes you have Docker and Docker Compose installed on your system.

## Clone the repository

git clone https://github.com/your-username/hangman-bdd-tdd-docker.git


## Build containers

The project utilizes a Dockerfile to build a PHP image with the necessary dependencies for the Hangman game. 
Navigate to the project directory and run:

``$ docker-compose build``



## Run application:


``$ docker-compose up -d`` 

This starts the Nginx container for web serving and the PHP-fpm container for application logic. They communicate securely using a shared Unix socket within a custom network.

``$ make app`` to enter the game container CLI

``$ make play`` to launch CLI game

## Clean Architecture with PHP

A Clean Architecture approach applied to the Hangman game in PHP.

**Key Concepts:**

* **Domain Logic Separation:** The core game logic resides in the domain layer, independent of any external frameworks or persistence mechanisms. This includes concepts like Word, Game, and related business rules (e.g., checking guesses, determining win/loss conditions).
* **Ports and Adapters:** The domain layer interacts with the outside world through interfaces (ports) and their concrete implementations (adapters).
    * Ports define the functionalities needed (e.g., WordRepository, GuessInput, Output).
    * Adapters provide specific implementations for those functionalities (e.g., database adapter for WordRepository, user interface adapter for GuessInput and Output).

**Benefits:**

* **Testability:** Pure domain logic is easier to unit test in isolation without relying on external dependencies.
* **Maintainability:** Changes to persistence or presentation layers don't impact the core game logic.
* **Flexibility:** Different adapters can be plugged in for various environments (e.g., in-memory storage for testing, database adapter for production).


## BDD and TDD Workflow

This project uses a combined BDD and TDD approach for development:

1. Define User Stories (BDD):

Start by outlining user stories in the features/hangman.feature file.
These stories describe how users interact with the game and the expected behavior.
Each story is broken down into scenarios, which represent specific interactions.

2. Write Failing Tests (TDD):

Based on the BDD scenarios, write failing tests (one test per scenario) in the tests/HangmanTest.php file.
These tests utilize PHPUnit to verify functionalities as they are implemented.
Initially, the tests will fail as the corresponding code doesn't exist.

## Branches:

- "main" is at initial state with business rules
- "feature/domain" is the realized domain by BDD / TDD. Can only be executed with tests
- "feature/cli" uses the Symfony Console component to interact and play
- "feature/api" shows a quick API built to interact
- "feature/ui" bootstraps a quick UI front with Vuejs

## Documentation:
see ./doc/ folder for business documentation and workflow used, plus other technical consideration

## Conclusion:

- domain respects business rules, hexagon is tested (build by tests)
- we don't really care about the infrastructure, there's simply non at start
- by implementing ports / adapters we can have a simple CLI app, an API with a js front or mobile
- we could add persistency with whatever system, infra is implementation detail
- don't focus on framework / tools, but domain
- easy to maintain, apply changes on business requirements
- no "anemic CRUD", code focuses on rules of the game