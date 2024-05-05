Feature: Providing Hints or Revealing Additional Letters

  Scenario: Requesting a Hint (Single Letter)
    Given a new game has started with the word "Secret"
    Given the player has guessed |s|c|
    When I request a hint
    Then one letter from the word should be revealed from the letters not already revealed
    And the number of remaining attempts should be decreased by the number of occurences of the letter

  Scenario: Requesting a Category Hint
    Given a new game has started with a limited number of attempts remaining
    When I request a hint to reveal the category
    Then the category should be displayed
    And the number of remaining attempts should be decreased by 1 multiplicate difficulty coefficient