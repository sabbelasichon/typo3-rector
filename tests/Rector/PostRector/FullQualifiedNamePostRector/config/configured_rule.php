<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../config/config_test.php');

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Typo3Option::PATHS_FULL_QUALIFIED_NAMESPACES, [
        '*full_qualified_namespace.php',
        '*non_full_qualified_namespace.php',
        '_temp_fixture_easy_testing/*some_other_tca.php',
        '_temp_fixture_easy_testing/*some_*.php',
    ]);
};
