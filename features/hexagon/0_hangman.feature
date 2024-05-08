Feature: Hangman Game

    Scenario: Player guesses a correct letter
        Given the secret word is "apple"
        And the player has 6 attempts
        When the player guesses the letter "a"
        Then the revealed word should be "a____"
        And the player should have 6 remaining attempts

    Scenario: Player guesses an incorrect letter
        Given the secret word is "apple"
        And the player has 6 attempts
        When the player guesses the letter "x"
        Then the revealed word should be "_____"
        And the player should have 5 remaining attempts

    Scenario: Player wins the game
        Given the secret word is "apple"
        And the player has 6 attempts
        When the player guesses the letters:
        |a|p|l|e|
        Then the revealed word should be "apple"
        And the game should be over

    Scenario: Player loses the game
        Given the secret word is "apple"
        And the player has 6 attempts
        When the player guesses the letters:
        |u|v|w|x|y|z|
        Then the revealed word should be "_____"
        And the game should be over
