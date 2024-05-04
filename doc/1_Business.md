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

### Additional Considerations

* **Visual Representation:** The application
