#!/bin/sh -l

# show errors
set -e

# script fails if trying to access to an undefined variable
set -u


# functions
note()
{
    MESSAGE=$1;

    printf "\n";
    echo "[NOTE] $MESSAGE";
    printf "\n";
}


# configure here
NESTED_DIRECTORY="typo3-rector-nested"
SCOPED_DIRECTORY="typo3-rector-scoped"

# ---------------------------

note "Starts"

note "Copying root files to $NESTED_DIRECTORY directory"
# Exclude the scoped/nested directories to prevent rsync from copying in a loop
rsync --exclude "$NESTED_DIRECTORY" --exclude "$SCOPED_DIRECTORY" -av * "$NESTED_DIRECTORY" --quiet

note "Running composer update without dev"
composer update --no-dev --no-progress --ansi --working-dir "$NESTED_DIRECTORY"

# Avoid Composer v2 platform checks (composer.json requires PHP 7.4+, but below we are running 7.3)
note "Disabling platform check"
composer config platform-check false

# 2. scope it
note "Running scoper to $SCOPED_DIRECTORY"
wget https://github.com/humbug/php-scoper/releases/download/0.14.1/php-scoper.phar -N --no-verbose

# Work around possible PHP memory limits
php -d memory_limit=-1 php-scoper.phar add-prefix bin config src templates stubs Migrations vendor utils/phpstan composer.json --output-dir "../$SCOPED_DIRECTORY" --config scoper.php --force --ansi --working-dir "$NESTED_DIRECTORY"


note "Dumping Composer Autoload"
composer dump-autoload --working-dir "$SCOPED_DIRECTORY" --ansi --optimize --classmap-authoritative --no-dev

rm -rf "$NESTED_DIRECTORY"


# copy metafiles needed for release
note "Copy metafiles like composer.json, .github etc to repository"
rm -f "$SCOPED_DIRECTORY/composer.json"
cp -R scoped/. "$SCOPED_DIRECTORY"

# make vendor/bin/rector runnable without "php"
chmod 777 "$SCOPED_DIRECTORY/bin/rector"
chmod 777 "$SCOPED_DIRECTORY/bin/rector.php"

note "Finished"
