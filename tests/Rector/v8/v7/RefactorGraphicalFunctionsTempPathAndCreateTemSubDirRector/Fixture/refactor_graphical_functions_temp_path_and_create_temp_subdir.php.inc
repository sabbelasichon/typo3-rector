<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v7\RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRector\Fixture;

use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRectorFixture
{
    public function method(): string
    {
        $graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
        $graphicalFunctions->tempPath = 'typo3temp';
        if($graphicalFunctions->createTempSubDir('var/transient/')) {
            // Yes could be created nice. Do further things here
        }
        return $graphicalFunctions->tempPath . 'var/transient/';
    }
}
?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v7\RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRector\Fixture;

use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRectorFixture
{
    public function method(): string
    {
        $graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
        $graphicalFunctions->tempPath = 'typo3temp';
        $createTempSubDir = function ($tempPath, $dirName) {
            if (GeneralUtility::isFirstPartOfStr($tempPath, PATH_site)) {
                $tmpPath = $tempPath;
            } else {
                $tmpPath = PATH_site . $tempPath;
            }
            if (!@is_dir($tmpPath . $dirName)) {
                GeneralUtility::mkdir_deep($tmpPath . $dirName);
                return @is_dir($tmpPath . $dirName);
            }
            return false;
        };
        if(call_user_func($createTempSubDir, 'typo3temp', 'var/transient/')) {
            // Yes could be created nice. Do further things here
        }
        return 'typo3temp/' . 'var/transient/';
    }
}
?>
