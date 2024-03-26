[![CI Status](https://github.com/TYPO3/CmsComposerInstallers/workflows/CI/badge.svg?branch=master)](https://github.com/TYPO3/CmsComposerInstallers/actions?query=workflow%3ACI)

# TYPO3 CMS Composer installers

This package acts as composer plugin in order to download and install TYPO3
core and extensions and put them into a directory structure which is suitable
for TYPO3 to work correctly.

The behavior of the installer can be influenced by configuration in the `extra`
section of the root `composer.json`.

## Options for extension composer.json

### `extension-key` (required)

```json
    "extra": {
        "typo3/cms": {
            "extension-key": "bootstrap_package"
        }
    }
```

Specifies the extension key. This is mandatory and extensions will stop to work
with version 4 of this package if not provided properly.

A warning is show by Composer if this key is missing in a extension.

## Options for project composer.json

### `web-dir`

```json
    "extra": {
        "typo3/cms": {
            "web-dir": "public"
        }
    }
```

You can specify a relative path from the base directory, where the public
document root should be located.

*The default value* is `"public"`, which means a `"public"` directory at the
same level as your root `composer.json`.

## Feedback / Bug reports / Contribution

Bug reports, feature requests and pull requests are welcome in the GitHub
repository: <https://github.com/TYPO3/CmsComposerInstallers>
