## The app directory:

- it's an extraction of logic that belongs to Domain, to help focus on interactions
with infra
- here handlers will boot use case and dispatch messages like GameUpdatedMessage
- for an easier read of directory tree, UseCases could be moved to App namespace