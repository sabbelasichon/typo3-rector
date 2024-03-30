## Table of Contents
1. [Examples in action](./examples_in_action.md)
2. [Overview of all rules](./all_rectors_overview.md)
3. [Installation](./installation.md)
4. [Configuration and Processing](./configuration_and_processing.md)
5. [Best practice guide](./best_practice_guide.md)
6. [Limitations](./limitations.md)
7. [Contribution](./contribution.md)

# What TYPO3 Rector cannot do for you

Some people expect simply too much of TYPO3 Rector.
To avoid these high expectations, this section in the documentation exists.

At the moment TYPO3 Rector is not able to refactor the following things:

1. SignalSlots to PSR-14 Events
2. eID to PSR-15 Middleware
3. ObjectManager to PSR-11 Dependency Injection
4. `$GLOBALS['TYPO3_DB']` to Doctrine DBAL (only a few simple cases)
5. Fully migrate TCA changes like `internal_type=file` to FAL

This list does not claim to be exhaustive. There are certainly many other things that TYPO3 Rector cannot yet take on.

Have a look at all the currently [available rules](all_rectors_overview.md)

## Known Drawbacks

### How to Apply Coding Standards?

Rector uses [nikic/php-parser](https://github.com/nikic/PHP-Parser/), built on technology called an *abstract syntax tree* (AST).
An AST doesn't know about spaces and when written to a file it produces poorly formatted
code in both PHP and docblock annotations. **That's why your project needs to have a coding standard tool** and a set of
formatting rules, so it can make Rector's output code nice and shiny again.
