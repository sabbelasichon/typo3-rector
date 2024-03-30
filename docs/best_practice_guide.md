## Table of Contents
1. [Examples in action](./examples_in_action.md)
1. [Overview of all rules](./all_rectors_overview.md)
1. [Installation](./installation.md)
1. [Configuration and Processing](./configuration_and_processing.md)
1. [Best practice guide](./best_practice_guide.md)
1. [Limitations](./limitations.md)
1. [Contribution](./contribution.md)

# Best practice guide

## What to use for

You can use TYPO3 Rector in various ways:

- checking existing code if there are left-out segments of the last upgrade
- evaluate upgrades and see what parts of your custom setup will be updated automatically
- partial execution of full core upgrades

## Guide for a good upgrade

### TLDR;

- apply older or current version rulesets first (if you're going from v8 to v10, apply v7/v8 sets first)
- add ClassAliasMap in case you're upgrading two versions to provide old classes to migrate (see [ClassAliasMap](#classaliasmap))
- apply rulesets stepwise by version; first TCA only, then full set or combined
- apply rulesets stepwise to your packages or multiple packages at once

### Starting

Starting with an upgrade should start with installing TYPO3 Rector and checking for the rector rules/sets of your current version, not the one you're targeting.
Often there are things that were missed out in previous upgrades while rector adds rulesets for those.

If you're on TYPO3 v8 you should start with applying the rulesets for v7 first and v8 afterwards.

Examples for often missed out upgrade steps:

- ext:lang key changes OR the full ext:lang replacement
- TCA renderType addition on `type="single"`
- TCA fieldwizard OR overrideChildTCA rewrite

### Ongoing upgrade

After making sure your current code base is properly upgraded, you go on with the actual upgrade process.
This requires manual action like allowing the core versions in your composer.json and ext_emconf.php files depending on your individual setup.

#### Applying rulesets

Depending on the amount of version steps you should add the ClassAliasMap as mentioned above for e.g. v8 if you're going from v8 to v10 directly.

Once again, you add your wanted/needed rulesets that should be separated by version.
It also comes in handy to divide between TCA and TYPO3 changes AND/OR your packages.

**The TYPO3 sets always include the TCA sets!**

TCA changes are often not that big in their impact but necessary. Also, custom packages do not necessarily provide that much own TCA.
Both of that is a reason to gather multiple packages for a combined TCA run with the following config:

```php
return RectorConfig::configure()
    ->withSets([
        Typo3SetList::TCA_95
    ])
    ->withPaths([([
        __DIR__ . '/packages/package_one',
        __DIR__ . '/packages/package_two',
        __DIR__ . '/packages/package_three',
    ]);
```

### ClassAliasMap

The ClassAliasMap is a TYPO3 specific feature.
It is used to allow migration of no longer existing class names to new class names.
Rector is not able to load the necessary ClassAliasMap on demand.
Those need to be provided via `extra` section inside `composer.json` of the project:

```json
{
    "extra": {
        "typo3/class-alias-loader": {
            "class-alias-maps": [
                "vendor/ssch/typo3-rector/Migrations/TYPO3/10.4/typo3/sysext/backend/Migrations/Code/ClassAliasMap.php",
                "vendor/ssch/typo3-rector/Migrations/TYPO3/10.4/typo3/sysext/core/Migrations/Code/ClassAliasMap.php",
                "vendor/ssch/typo3-rector/Migrations/TYPO3/12.0/typo3/sysext/backend/Migrations/Code/ClassAliasMap.php",
                "vendor/ssch/typo3-rector/Migrations/TYPO3/12.0/typo3/sysext/frontend/Migrations/Code/ClassAliasMap.php"
            ]
        }
    }
}
```

Provide the ClassAliasMap files of all necessary extensions for all necessary versions.

---
**Be aware!**
There are limitations to the TCA detection.

TYPO3 Rector can only detect TCA if the TCA is valid, which means there is a 'ctrl' and a 'columns' key:

```php
return [
    'ctrl' => [],
    'columns' => [],
];
```

**INFO**
TCA in `Configuration/Override/` is also migrated if necessary.

---

The non-TCA rules are often a little more specific and should be applied in a separate step with the according set, e.g. `Typo3SetList::TYPO3_95`.

Those rules bring immense value as you don't have to find the replacement of classes and the actual changelog as it is provided for you already on execution.
With `--dry-run` you can process the ruleset without applying the changes giving you a perfect overview **before** changing your code.

You can focus on testing and possibly learning the new implementation of previous functions.

## Special cases

There are changes that TYPO3 Rector knows of but cannot fully complete.
