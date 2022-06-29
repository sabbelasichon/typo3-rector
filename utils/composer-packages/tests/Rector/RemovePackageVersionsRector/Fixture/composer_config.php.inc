<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../services.php');
    $composerExtensions = [new \Rector\Composer\ValueObject\PackageAndVersion('foo/bar', '^1.0')];
};

?>
-----
<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../services.php');
    $composerExtensions = [new \Rector\Composer\ValueObject\RenamePackage('foo/bar', 'foo/baz')];
};

?>
