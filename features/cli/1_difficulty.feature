Feature: Difficulty Levels

  Scenario: Choosing Easy Difficulty
    Given I start a new game
    When I choose the "Easy" difficulty level
    Then a word with a shorter length should be chosen (optional, depending on implementation)

  Scenario: Choosing Hard Difficulty
    Given I start a new game
    When I choose the "Hard" difficulty level
    Then a word with a longer length should be chosen (optional, depending on implementation)