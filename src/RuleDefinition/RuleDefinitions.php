<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\RuleDefinition;

final class RuleDefinitions
{
    public const COMPOSER_PATCH = <<<DESCRIPTION


> [!IMPORTANT]
> If you use this rule, please install the [cweagans/composer-patches](https://packagist.org/packages/cweagans/composer-patches) package and add the following snippet to your composer.json:

```json
{
    "extra": {
        "patches": {
            "rector/rector": [
                "https://raw.githubusercontent.com/sabbelasichon/typo3-rector/refs/heads/2.x/patches/rector-rector-rules-typedeclaration-nodeanalyzer-autowiredclassmethodorpropertyanalyzer-php.patch",
                "https://raw.githubusercontent.com/sabbelasichon/typo3-rector/refs/heads/2.x/patches/rector-rector-src-nodemanipulator-classdependencymanipulator-php.patch",
                "https://raw.githubusercontent.com/sabbelasichon/typo3-rector/refs/heads/2.x/patches/rector-rector-src-phpparser-node-nodefactory-php.patch"
            ]
        }
    }
}
```
DESCRIPTION;

}
