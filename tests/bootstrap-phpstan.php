<?php

declare(strict_types=1);

use Rector\Rector\AbstractRector;

require_once __DIR__ . '/../vendor/autoload.php';

// Rector loads its autoloader lazily, on the first class lookup. That also includes Rector\RuleDoc classes for their
// aliases, so looking one of them up first would include it twice and end in a "Cannot redeclare class" fatal error.
class_exists(AbstractRector::class);

// silent deprecations, since we test them
error_reporting(E_ALL & ~E_NOTICE | E_DEPRECATED);
// performance boost
gc_disable();
// define some globals
