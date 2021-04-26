<?php

use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ClassWithTemplateServiceSplitConfArrayRector
{
    public function method(): void
    {
        $splitConfig = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\TemplateService::class)->splitConfArray($conf, $splitCount);
    }
}

?>
-----
<?php

use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ClassWithTemplateServiceSplitConfArrayRector
{
    public function method(): void
    {
        $splitConfig = GeneralUtility::makeInstance(TypoScriptService::class)->explodeConfigurationForOptionSplit($conf, $splitCount);
    }
}

?>
