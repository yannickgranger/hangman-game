Feature: Tracking Statistics (Wins, Losses, Average Guesses)

  Scenario: Recording Game Statistics (Win)
    Given a new game has started
    When a player successfully guesses all letters in the word
    Then a win should be recorded for the player
    And the total number of games played should be incremented

  Scenario: Recording Game Statistics (Loss)
    Given a new game has started
    When a player uses up all attempts
    Then a loss should be recorded for the player
    And the total number of games played should be incremented

  Scenario: Displaying Game Statistics
    Given multiple games have been played
    When I request game statistics
    Then the total number of wins, losses, and average number of guesses should be displayed