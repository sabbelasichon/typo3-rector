[![Latest Stable Version](https://poser.pugx.org/ssch/typo3-rector/v/stable.svg)](https://packagist.org/packages/ssch/typo3-rector)
[![Total Downloads](https://poser.pugx.org/ssch/typo3-rector/d/total.svg)](https://packagist.org/packages/ssch/typo3-rector)
[![Monthly Downloads](https://poser.pugx.org/ssch/typo3-rector/d/monthly)](https://packagist.org/packages/ssch/typo3-rector)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/schreiberten)

> [!WARNING]
> :heavy_exclamation_mark: Never run this tool on production! Always run it on development environment where code is under version control (e.g. git).
> Review and test changes before releasing to production. Code migrations could potentionally break your website!

# Rector for TYPO3

This project lets you apply instant upgrades and refactoring to your [TYPO3 Website](https://get.typo3.org/) and
[extension](https://extensions.typo3.org) code, making it easier to migrate between TYPO3 releases and keeping your code
free from deprecation.

It extends the [Rector](https://github.com/rectorphp/rector) project, which aims to provide instant upgrades and refactoring for any PHP code (5.3+).

|                    | URL                                                          |
|--------------------|--------------------------------------------------------------|
| **Repository:**    | https://github.com/sabbelasichon/typo3-rector                |
| **Documentation:** | https://github.com/sabbelasichon/typo3-rector/tree/main/docs |
| **Packagist:**     | https://packagist.org/packages/ssch/typo3-rector             |
| **Website:**       | https://www.typo3-rector.com                                 |

## Installation

TYPO3 Rector requires at least PHP 7.4 but is also compatible with PHP 8.
You can find more details about the installation in our [installation documentation](docs/installation.md).

You can install the package via composer:

```bash
composer require --dev ssch/typo3-rector
```

You can create the rector config file with:

```bash
vendor/bin/typo3-init
```

## Usage

To see the code migrations that Rector will do, run:

```bash
vendor/bin/rector process --dry-run
```

and when you want to execute the migrations run:

```bash
vendor/bin/rector process
```

## Contributing

Please see [CONTRIBUTING](docs/contribution.md) for details.

## Funding/Sponsoring

Help us out and sponsor our work! Visit our website [typo3-rector.com](https://www.typo3-rector.com) for more info.

This makes it possible to invest more time to keep the project alive and create even more rules for automated migration.

## Support

Please post questions in the TYPO3 Slack channel [#ext-typo3-rector](https://typo3.slack.com/archives/C019R5LAA6A)
or feel free to open an issue or start a discussion on GitHub.

## Credits

Many thanks to [Tomas Votruba](https://tomasvotruba.com) for maintaining Rector.
Many thanks to [All Contributors](https://github.com/sabbelasichon/typo3-rector/graphs/contributors).

Follow us on X:
- [TYPO3 Rector](https://twitter.com/TYPO3Rector)
- [Sebastian](https://twitter.com/schreiberten)
- [Henrik](https://twitter.com/he_coli)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Known Drawbacks

### How to Apply Coding Standards?

Rector uses [nikic/php-parser](https://github.com/nikic/PHP-Parser/), built on technology called an
*abstract syntax tree* (AST). An AST doesn't know about spaces and when written to a file it produces poorly formatted
code in both PHP and docblock annotations. **That's why your project needs to have a coding standard tool** and a set of
formatting rules, so it can make Rector's output code nice and shiny again.

We're using [ECS](https://github.com/symplify/easy-coding-standard) with [this setup](ecs.php).
