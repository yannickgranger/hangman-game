Feature: Providing Hints or Revealing Additional Letters

  Scenario: Requesting a Hint (Single Letter)
    Given a new game has started with the word "Secret"
    When I request a hint
    Then one letter from the word should be revealed (e.g., "S_cret")
    And the number of remaining attempts should be decreased by one (optional)

  Scenario: Requesting a Hint (Full Word Reveal)
    Given a new game has started with a limited number of attempts remaining
    When I request a hint to reveal the entire word
    Then the complete word should be revealed
    And the game should be considered lost