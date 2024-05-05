Feature: initialize game and guess letter with the commands initialize and guessLetter

  Scenario: launch the console game
    Given i launch the command "initialize"
    And the output should be "Welcome to Hangman! The word has \d letters."

  Scenario: guess a letter game
    Given I have initialized a game
    Given the output should be "Welcome to Hangman! The word has \d letters."
    And I guess the letter "a" using the "GuessLetterCommand"