<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v3\RefactorMethodFileContentRector\Fixture;

use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    public function main($content, $conf)
    {
        $filename = 'foo.txt';
        return $GLOBALS['TSFE']->tmpl->fileContent($filename);
    }

    public function fileContent($filename)
    {
        return GeneralUtility::makeInstance(TemplateService::class)->fileContent($filename);
    }

    public function fileContent2($filename)
    {
        $templateService =  GeneralUtility::makeInstance(TemplateService::class);
        return $templateService->fileContent($filename);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v3\RefactorMethodFileContentRector\Fixture;

use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    public function main($content, $conf)
    {
        $filename = 'foo.txt';
        return $GLOBALS['TSFE']->tmpl->getFileName($filename) ? file_get_contents($filename) : null;
    }

    public function fileContent($filename)
    {
        return GeneralUtility::makeInstance(TemplateService::class)->getFileName($filename) ? file_get_contents($filename) : null;
    }

    public function fileContent2($filename)
    {
        $templateService =  GeneralUtility::makeInstance(TemplateService::class);
        return $templateService->getFileName($filename) ? file_get_contents($filename) : null;
    }
}

?>
