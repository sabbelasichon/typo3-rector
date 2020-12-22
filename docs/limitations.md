# What Rector cannot do for you

Some people expecting simply too much of typo3-rector.
To avoid these high expectations this section in the documentation exists.

At the moment typo3-rector is not able to refactor the following things:

1. SignalSlots to PSR-14 Events
2. eID to PSR-15 Middleware
3. ObjectManager to PSR-11 Dependency Injection
4. $GLOBALS['TYPO3_DB'] to Doctrine DBAL (only a few simple cases)

This list does not claim to be exhaustive. There are certainly many other things that typo3-rector cannot yet take on.

Have a look at all the currently [available rules](all_rectors_overview.md)
