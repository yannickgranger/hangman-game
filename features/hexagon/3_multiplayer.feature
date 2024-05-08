Feature: Multiplayer (Shared Word, Turn-based)

  Scenario: Play a game with three players (cooperative victory)
    Given players with names "Alice", "Bob", and "Charlie" exist
    When a game is created with the word "secret" (shared word)
    Then Alice, Bob, and Charlie should see the following word: "_____"

    When Alice guesses the letter "s"
    Then Alice, Bob, and Charlie should see the following word: "s_____"

    When Bob guesses the letter "e"
    Then Alice, Bob, and Charlie should see the following word: "se__e_"

    When Charlie guesses the letter "c"
    Then Alice, Bob, and Charlie should see the following word: "sec_e_"

    When Alice guesses the letter "r"
    Then Alice, Bob, and Charlie should see the following word: "secre_"

    When Bob guesses the letter "t"
    Then Alice, Bob, and Charlie should see the following word: "secret"
    Then the game state should be "GAME_WON" (visible to all)
    Then all players (Alice, Bob, and Charlie) should win the game (shared victory)

  Scenario: Play a game with three players (competitive, shared loss)
    Given players with names "Alice", "Bob", and "Charlie" exist
    When a game is created with the word "secret" (shared word)
    Then Alice, Bob, and Charlie should see the following word: "_____"

    When Alice guesses the letter "s"
    Then Alice, Bob, and Charlie should see the following word: "s_____"

    When Bob guesses the letter "x" (incorrect guess)
    Then the number of incorrect guesses remaining should decrease by 1 (visible to all)
    Then Alice, Bob, and Charlie should see the following word: "s_____"

    When Charlie guesses the letter "z" (incorrect guess)
    Then the number of incorrect guesses remaining should decrease by 1 (visible to all)
    Then Alice, Bob, and Charlie should see the following word: "s_____"

    When Alice guesses the letter "u" (incorrect guess)
    Then the number of incorrect guesses remaining should decrease by 1 (visible to all)
    Then Alice, Bob, and Charlie should see the following word: "s_____"

    When Bob guesses the letter "w" (incorrect guess)
    Then the number of incorrect guesses remaining should decrease by 1 (visible to all)
    Then Alice, Bob, and Charlie should see the following word: "s_____"

    When Bob guesses the letter "q" (incorrect guess)
    Then the number of incorrect guesses remaining should decrease by 1 (visible to all)
    Then Alice, Bob, and Charlie should see the following word: "s_____"

    When Alice guesses the letter "p" (incorrect guess)
    Then the game state should be "GAME_LOST" (visible to all)
    Then all players (Alice, Bob, and Charlie) should lose the game (shared loss)
