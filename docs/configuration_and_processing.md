# Configuration and Processing

This library ships already with a bunch of configuration files organized by TYPO3 version.
To get you started quickly run the following command inside the root directory of your project:

```bash
./vendor/bin/typo3-rector typo3-init
```

The command generates a basic configuration skeleton which you can adapt to your needs.
The file is fully of comments so you can follow along what is going on.

For more configuration options see [Rector README](https://github.com/rectorphp/rector#configuration).

After your adopt the configuration to your needs, run typo3-rector to simulate (hence the option -n) the future code fixes:

```bash
./vendor/bin/typo3-rector process packages/my_custom_extension --dry-run
```

Check if everything makes sense and run the process command without the `--dry-run` option to apply the changes.
