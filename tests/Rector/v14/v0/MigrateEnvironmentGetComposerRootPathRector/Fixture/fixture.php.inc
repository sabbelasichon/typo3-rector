<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateEnvironmentGetComposerRootPathRector\Fixture;

use TYPO3\CMS\Core\Core\Environment;

$rootPath = Environment::getComposerRootPath();

$targetPath = 'foo';
$relativePath = substr($targetPath, strlen(Environment::getComposerRootPath()));
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateEnvironmentGetComposerRootPathRector\Fixture;

use TYPO3\CMS\Core\Core\Environment;

$rootPath = Environment::getProjectPath();

$targetPath = 'foo';
$relativePath = substr($targetPath, strlen(Environment::getProjectPath()));
?>
