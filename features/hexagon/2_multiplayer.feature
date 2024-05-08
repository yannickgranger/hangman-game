Feature: Multiplayer Hangman - Shared Screen

#  Scenario Outline: Join a game with too many players
#    Given <numberOfPlayers> players exist named "<player1Name>", "<player2Name>",
#    And a game exists with the word "secret"
#    When the first player "<player1Name>" joins the game
#    Then "<player1Name>" should see a welcome message with the word and remaining guesses
#    And the shared screen should display the word with underscores
#
#    When the second player "<player2Name>" joins the game
#    Then "<player2Name>" should see a message welcoming them and informing them of existing players  # Show existing players if applicable
#    And the shared screen should update to display the current word for "<player2Name>"
#
#    When the additional player "<otherPlayerName>" joins the game
#    Then "<otherPlayerName>" should be informed the game is already full  # Game overflow message
#
#    Examples:
#      | numberOfPlayers | player1Name | player2Name | otherPlayerName |
#      | 2                | Alice       | Bob         | Charlie         |
#      | 3                | Alice       | Bob         | Charlie         |


  Scenario: Join a game and play (2 players)
    Given a player named "Alice" exists
    And a game exists with the word "secret"
    When "Alice" joins the game
    Then "Alice" should see a welcome message with the word and remaining guesses
    And the shared screen should display the word with underscores

    When "Alice" guesses the letter "s"
    Then players should see a message indicating a good guess and that "s" is in the word
    And the shared screen should update the word to reflect the guessed letter

    Given a player named "Bob" exists
    When "Bob" joins the game
    Then players should see a message welcoming them and informing them "Alice" has joined
    And the shared screen should update the word to reflect the guessed letter

    When "Bob" guesses the letter "e"
    Then players should see a message indicating a good guess and that "e" is in the word
    And the shared screen should update the word to reflect the guessed letter

    When "Alice" guesses the letter "c"
    Then players should see a message indicating a good guess and that "c" is in the word
    And the shared screen should update the word to reflect the guessed letter

    When "Bob" guesses the letter "r"
    Then players should see a message indicating a good guess and that "r" is in the word
    And the shared screen should update the word to reflect the guessed letter

    When "Alice" guesses the letter "t"
    Then players should see a message congratulating them on winning and revealing the word "secret"

    When "Bob" tries to guess a letter
    Then "Bob" should be informed that the game is over

