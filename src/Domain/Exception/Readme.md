# Domain exception vs infra exception

Sometimes you may find that you have a group of business exceptions and some more top-level exceptions
that may be the same.

For example:

- in Api context, user sends a "1" or a "" as a letter input

1. Your top-level validation should raise an exception before using a corrupted to call the domain
2. Your domain should also have its own validation

Remember that domain logic should cover business rules. But you can have duplicate / redundant validation.
This is desired behavior, because:
- if you're not in API, you may use another class as entrypoint
- you want your domain to stay sage whatever happens


## InvalidGuessFormatException or InvalidGuessException ?

The choice of your exceptions is up to you. You will maintain the application.
Just a note here:

- InvalidGuessFormatException: this is a top-level exception. The api receives text or numeric.
  This is minimal validation.

- InvalidGuessException is raised in domain. It can cover business cases.
    - For example: a player asked 2x the same letter to be guessed. You can consider it a domain error or not.
    - The player try to guess a chars like '!', '?', ',', '*' or a foreign language character
      Handling this makes you realize that business rules may be refined to cover edge cases.
