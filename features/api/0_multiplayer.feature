Feature: Multiplayer Hangman API

  Scenario: Start a new game
    When a POST request is sent to "/api/games" with data: {"word": "secret", "maxPlayers": 2}
    Then a new game should be created with the word "secret" and maximum of 2 players
    Then a message should be published to Mercure indicating a new game creation (gameId: 123)  # Assuming gameId is generated

  Scenario: Join an existing game
    When a POST request is sent to "/api/games/{gameId}/join" with data: {"username": "Bob"} (replace {gameId} with actual ID)
    Then the user with username "Bob" should join the game with ID {gameId} (if it exists)
    Then the user should receive updates on the game state through Mercure subscriptions

  Scenario: Invalid request body
    When a POST request is sent to "/api/games" with invalid data
    Then the API should return a bad request error (400) with a descriptive message

  Scenario: Missing required data in request body
    When a POST request is sent to "/api/games" missing required data (e.g., word)
    Then the API should return a bad request error (400) with a descriptive message

  Scenario: Guess a letter
    Given a game exists with the word "secret" (gameId: 123)
    When a POST request is sent to "/api/games/{gameId}/guess" with data: {"letter": "s"} (replace {gameId} with actual ID)
    Then the game state should be updated with the guessed letter "s"
    Then a message should be published to Mercure indicating a letter guess (gameId: 123, letter: "s")

  Scenario: List available games
    When a GET request is sent to "/api/games"
    Then the API should respond with a list of available games (if any), including game ID and basic information

