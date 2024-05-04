Feature: Gameplay with fixed word "ananas"

  @new-game
  Scenario: New Game
    Given I start a new game
    Then a random word should be chosen
    And the number of remaining attempts should be displayed
    And the word should be displayed with underscore representing hidden letters

  @guess-correct
  Scenario: Guessing a Letter (Correct)
    Given a new game has started
    When I guess a letter that exists in the word
    Then the letter should be revealed in all its correct positions
    And the number of remaining attempts should not change

  @guess-incorrect
  Scenario: Guessing a Letter (Incorrect)
    Given a new game has started
    When I guess a letter that does not exist in the word
    Then one attempt should be deducted from the remaining attempts
    And the word should remain hidden with underscore

  @win
  Scenario: Winning the Game
    Given a new game has started
    When I guess all the letters in the word correctly
    Then a message indicating victory should be displayed
    And the complete word should be shown

  @loose
  Scenario: Losing the Game
    Given a new game has started
    When I use up all my attempts by guessing incorrect letters
    Then a message indicating defeat should be displayed
    And the complete word should be shown