<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Ssch\TYPO3Rector\Configuration\Typo3Option;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../config/config_test.php');
    $rectorConfig->importNames();
    $parameters->set(Typo3Option::PATHS_FULL_QUALIFIED_NAMESPACES, [
        '*full_qualified_namespace.php',
        '*non_full_qualified_namespace.php',
        '_temp_fixture_easy_testing/*some_other_tca.php',
        '_temp_fixture_easy_testing/*some_*.php',
    ]);
};
