Feature: Multiplayer Hangman API (Turn-based)

  Scenario: Start a new game and specify player name
    When a POST request is sent to "/api/games" with data: {"word": "secret", "maxPlayers": 2, "startingPlayer": "Alice"}
    Then a new game should be created with the word "secret" and maximum of 2 players
    Then the current player's turn should be "Alice"
    Then a message should be published to Mercure indicating a new game creation (gameId: 123, startingPlayer: "Alice")

  Scenario: Join an existing game and specify username
    When a POST request is sent to "/api/games/{gameId}/join" with data: {"username": "Bob"} (replace {gameId} with actual ID)
    Then the user with username "Bob" should join the game with ID {gameId} (if it exists)
    Then the user should receive updates on the game state through Mercure subscriptions

  Scenario: Guess a letter during your turn
    Given a game exists with the word "secret" and Alice's turn (gameId: 123)
    When a POST request is sent to "/api/games/{gameId}/guess" with data: {"letter": "s"} (replace {gameId} with actual ID) by the user with username "Alice"
    Then the game state should be updated with the guessed letter "s"
    Then the current player's turn should be switched (e.g., to Bob)
    Then a message should be published to Mercure indicating a letter guess (gameId: 123, letter: "s", currentPlayer: "Bob")

  Scenario: Guess a letter during another player's turn
    Given a game exists with the word "secret" and Bob's turn (gameId: 123)
    When a POST request is sent to "/api/games/{gameId}/guess" with data: {"letter": "s"} (replace {gameId} with actual ID) by the user with username "Alice"
    Then the API should respond with a forbidden error (403) with a message indicating it's not Alice's turn

  Scenario: List available games with player information
    When a GET request is sent to "/api/games"
    Then the API should respond with a list of available games (if any), including:
    * Game ID
    * Word length (hidden)
    * Number of players
    * Current player's username (if any)

