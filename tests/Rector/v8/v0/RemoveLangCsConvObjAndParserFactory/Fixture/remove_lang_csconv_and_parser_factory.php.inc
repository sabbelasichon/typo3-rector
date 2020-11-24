<?php

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RemoveLangCsConvObjAndParserFactory
{
    public function method(): void
    {
        $languageService = GeneralUtility::makeInstance(LanguageService::class);
        $charsetConverter = $languageService->csConvObj();
        $Localization = $languageService->parserFactory();
    }
}
?>
-----
<?php

use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Localization\LocalizationFactory;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
class RemoveLangCsConvObjAndParserFactory
{
    public function method(): void
    {
        $languageService = GeneralUtility::makeInstance(LanguageService::class);
        $charsetConverter = GeneralUtility::makeInstance(CharsetConverter::class);
        $Localization = GeneralUtility::makeInstance(LocalizationFactory::class);
    }
}
?>
