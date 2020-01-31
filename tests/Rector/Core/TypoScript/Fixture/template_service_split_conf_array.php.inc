<?php

final class ClassWithTemplateServiceSplitConfArrayRector
{
    public function method(): void
    {
        $splitConfig = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\TemplateService::class)->splitConfArray($conf, $splitCount);
    }
}

?>
-----
<?php

final class ClassWithTemplateServiceSplitConfArrayRector
{
    public function method(): void
    {
        $splitConfig = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\TypoScriptService::class)->explodeConfigurationForOptionSplit($conf, $splitCount);
    }
}

?>
