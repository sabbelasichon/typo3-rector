<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../services.php');

    $composerExtensions = [];
};

?>
-----
<?php

use Rector\Composer\ValueObject\PackageAndVersion;
use Rector\Composer\ValueObject\RenamePackage;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../services.php');

    $composerExtensions = [new PackageAndVersion('foo/bar', '^1.0'), new RenamePackage('foo/bar', 'foo/baz')];
};

?>
