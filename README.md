# Rector for TYPO3

Apply automatic fixes on your TYPO3 code.

[![Coverage Status](https://img.shields.io/coveralls/sabbelasichon/typo3-rector/master.svg?style=flat-square)](https://coveralls.io/github/sabbelasichon/typo3-rector?branch=master)

## Installation

Install the library.

```bash
$ composer require --dev ssch/typo3-rector
```

## Disclaimer

This is just a POC at this moment.

*Do you have an idea about what else this tool could do? Please share it in the issue queue. Pull requests are also warmly welcomed!*


## What Can Rector Do for You?

...**look at the overview of [all available TYPO3 Rectors](/docs/AllRectorsOverview.md)** with before/after diffs and configuration examples.

## Contributing

Want to help? Great!

### Fork the project

Fork this project into your own account.

### Install typo3-rector

Install the project using composer:
```bash
git clone https://github.com/your-account/typo3-rector.git
cd typo3-rector
composer install
```

### Pick an issue from the list

https://github.com/sabbelasichon/typo3-rector/issues You can filter by tags

### Assign the issue to yourself

Assign the issue to yourself so others can see that you are working on it.

### Create Rector

1. Find a place to store the Rector in `src/Rector`. What is the most logical folder structure?
2. Create a stubb class in `stubs` if needed.
3. Write your rector.
4. Make sure your new Rector class can is autoloaded: `composer du`
5. Write a test for your rector.

### All tests must be green
Make sure you have a test in place for your Rector

All unit tests must pass before sumbmitting a pull request.

```bash
./bin/phpunit
```

### Submit your changes

Great, now you can submit your changes in a pull request
