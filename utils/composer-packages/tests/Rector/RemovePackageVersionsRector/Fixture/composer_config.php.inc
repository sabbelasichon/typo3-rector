<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $rectorConfig->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    $composerExtensions = [new \Rector\Composer\ValueObject\PackageAndVersion('foo/bar', '^1.0')];
};

?>
-----
<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $rectorConfig->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    $composerExtensions = [];
};

?>
