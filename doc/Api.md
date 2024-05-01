# Api 

With http interactions, PHP does not keep memory between requests.
So we need a persistence mechanism that lives more than the request -> response cycle.
We choosed redis because it's the simplest and fastest store.

For future evolutions, like statistics, players login, rewards, we may need to use
a SQL database.

To use redis we add a docker environment.