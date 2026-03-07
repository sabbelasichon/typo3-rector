# Copilot Instructions for TYPO3 Rector

## Build, test, and lint commands

- Install dependencies: `composer install`
- Lint PHP syntax: `composer ci:php:lint`
- Check coding style: `composer ci:check-style`
- Run static analysis: `composer ci:php:stan`
- Run tests: `composer ci:tests:unit` or `vendor/bin/phpunit`
- Run a single test file: `vendor/bin/phpunit tests/Rector/v14/v0/RemoveIsStaticControlOptionRector/RemoveIsStaticControlOptionRectorTest.php`
- Run a single test method: `vendor/bin/phpunit --filter test tests/Rector/v14/v0/RemoveIsStaticControlOptionRector/RemoveIsStaticControlOptionRectorTest.php`
- Repository-specific CI checks used in workflows:
  - `composer validate --strict --ansi`
  - `php build/scripts/sameBeforeAfterFixtureDetector.php`
  - `php build/scripts/noPhpFileInFixturesDetector.php`
  - `composer ci:check-typo3-rector`

## High-level architecture

- This repository is a Rector extension (`type: rector-extension`) focused on TYPO3 migrations; runtime behavior is driven by Rector config sets rather than a standalone app entrypoint.
- Rule set entry points are constants in:
  - `src/Set/Typo3SetList.php` (major-version sets like TYPO3_10 ... TYPO3_14)
  - `src/Set/Typo3LevelSetList.php` (cumulative “up-to” sets)
- Set config lives in `config/`:
  - top-level files (e.g. `config/typo3-14.php`) compose multiple version-specific imports under `config/vXX/`
  - level files under `config/level/` combine “up to previous” + “current major set”
- `config/config.php` wires shared services (filesystem adapters, node analyzers/resolvers/factories) into Rector’s DI container; `config/config_test.php` imports this baseline and enables import-name behavior used by tests.
- Concrete migration rules are in `rules/`, grouped by domain and TYPO3 version (`TYPO310`, `TYPO311`, ... `TYPO314`, plus `CodeQuality`, `General`, `TypeDeclaration`).
- Tests mirror rule organization under `tests/Rector/...`; each test typically extends `Rector\Testing\PHPUnit\AbstractRectorTestCase`, points to a local `config/configured_rule.php`, and runs fixtures from `Fixture/*.php.inc`.
- Fixture files encode before/after transformations separated by `-----`, which is the canonical way rule behavior is asserted.

## Key repository conventions

- Keep contributed code PHP 7.4-compatible (project requirement and CI matrix baseline).
- New rules are expected to follow existing Rector patterns from `docs/contribution.md`: narrow `getNodeTypes()`, flat early-return `refactor()`, and use existing helpers before introducing new abstractions.
- Rule classes commonly implement `Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface` and include a changelog link plus `RuleDefinition` code samples.
- Test fixtures should be `*.php.inc` with before/after sections if code should be migrated, otherwise only the code that should not be changed; use `Source/` for auxiliary classes that support tests but must not be transformed.
- Formatting of fixture/assertion files is intentionally relaxed/skipped by tooling; do not normalize them aggressively.
