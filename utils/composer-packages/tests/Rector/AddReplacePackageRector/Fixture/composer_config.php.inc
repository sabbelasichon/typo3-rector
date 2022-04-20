<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../services.php');
    $composerExtensions = [];
};

?>
-----
<?php

use Rector\Composer\ValueObject\RenamePackage;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../services.php');
    $composerExtensions = [new RenamePackage('typo3-ter/news', 'georgringer/news')];
};

?>
