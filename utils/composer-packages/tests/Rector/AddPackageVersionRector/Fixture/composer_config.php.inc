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
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../services.php');

    $composerExtensions = [new PackageAndVersion('foo/bar', '^1.0')];
};

?>
