Feature: Allowing Multiple Players

  Scenario: Starting a New Game with Multiple Players
    Given two players are ready to play
    When a new game is started with multiple players enabled
    Then the game should take turns from each player
    And the remaining attempts should be shared among players

  Scenario: Losing a Turn in Multiplayer Game
    Given a new game has started with multiple players
    When a player guesses an incorrect letter
    Then the turn should be passed to the next player
    And the remaining attempts should not be deducted from the next player's turn