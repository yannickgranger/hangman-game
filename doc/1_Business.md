## Business Rules (.md) - Hangman Game

This document outlines the business rules for the Hangman game application. These rules define the logic and behavior of the game from a business perspective.

### Introduction

The Hangman game is a word-guessing game where players try to guess a secret word letter by letter. 
This document details the rules for a minimalistic but correct UX.

### Gameplay Rules


1. **Game Setup:**
    * The application randomly selects a word from a predefined word list.
    * The word length can be configurable or limited to a specific range. (Optional)
    * Players are given a set number of attempts to guess the word correctly. (e.g., 6 attempts)
    * The initial display shows the word length with underscores representing unguessed letters.
2. **Guessing Letters:**
    * Players can guess one letter at a time.
    * Case-insensitive guessing can be implemented (all guesses converted to uppercase or lowercase for consistency).
    * If the guessed letter is present in the word:
        * The revealed word display updates to show the guessed letter in its correct positions.
    * If the guessed letter is not present in the word:
        * The player loses one attempt.

3. **Winning and Losing:**
    * The player wins if they successfully guess all the letters in the word before running out of attempts.
    * The player loses if they exhaust all their attempts without guessing the word correctly.

4. **Duplicate Guesses:**
    * Players cannot guess the same letter twice.
    * The application should ignore duplicate guesses or inform the player they already guessed that letter.

5. **Word List:**
    * The word list should consist of appropriate words (avoid offensive or overly obscure words).
    * The difficulty level can be adjusted by controlling the word length or using different word lists (e.g., easy, medium, hard)

6. **Hint:**
   *  A player can request a hint, which mean a letter will be revealed, randomly choosed between unrevealed letters. The number of occurences of this letter are deducted from the remaining attempts. 
   A hint is requestable only if there's remaining unrevealed letters.



7. **Difficulty:**
   ```
   1. Word Length:
      Easy: Choose shorter words (4-6 letters) to give players more attempts and a higher chance of guessing correctly.
      Medium: Use words of moderate length (7-8 letters) to provide a balanced challenge.
      Hard: Select longer words (9+ letters) that require more careful deduction and strategic guessing.
   
   2. Number of Allowed Attempts:
      Easy: Increase the number of allowed attempts to give players more room for error.
      Medium: Use a moderate number of attempts (around 6-8) to strike a balance between challenge and frustration.
      Hard: Decrease the number of allowed attempts (4-5) to create a more intense game where each guess needs to be well-considered.
   
   3. Providing Hints:
      Easy: Offer more frequent hints, such as revealing a random vowel or a consonant that hasn't been guessed yet.
      Medium: Allow players to request hints at any point, but limit the number of available hints per game.
      Hard: Restrict or eliminate access to hints, forcing players to rely solely on their deduction skills.

   4. Categorized Word Lists:
      Easy: Use word lists with simpler vocabulary and common words.
      Medium: Choose words from a broader range of categories, including some less common words.
      Hard: Utilize word lists containing complex vocabulary, obscure terms, or proper nouns.

   5. Dynamic Difficulty Adjustment:
      Implement a system that adjusts difficulty based on player performance. If a player is struggling, increase the word length or offer more hints on subsequent rounds. Conversely, for a player who excels, decrease the word length or limit hints.
      Implementation Considerations:
      You can create configuration settings or difficulty levels to allow players to choose their preferred challenge.
      Difficulty settings can be integrated with the word selection process to ensure appropriate word choices based on the chosen difficulty.
      Consider a combination of these approaches to create a more nuanced difficulty system that adapts to player skill.
      
      Additional Ideas:
      Introduce a time limit for guesses to add an extra layer of pressure in harder difficulties.
      Implement a scoring system based on the number of guesses remaining and the number of hints used to encourage efficient play.
      Allow players to unlock new difficulty levels or word categories as they progress through the game.
      By incorporating these suggestions, you can provide a more engaging and customizable experience for players of all skill levels.
   ```

### Additional Considerations

* **Visual Representation:** The application
