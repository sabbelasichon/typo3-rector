<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap-phpstan.php';

// autoload rector php-parser first with local paths
// build preload file to autoload local php-parser instead of phpstan one, e.g. in case of early upgrade
exec('php vendor/rector/rector-src/build/build-preload.php .');
sleep(1);

require __DIR__ . '/../preload.php';
unlink(__DIR__ . '/../preload.php');
