<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v7\RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRector\Fixture;

use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRectorFixture
{
    public function method(): string
    {
        $graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
        $graphicalFunctions->createTempSubDir('var/transient/');
        return $graphicalFunctions->tempPath . 'var/transient/';
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v7\RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRector\Fixture;

use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRectorFixture
{
    public function method(): string
    {
        $graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
        GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/var/transient/');
        return 'typo3temp/' . 'var/transient/';
    }
}
?>
