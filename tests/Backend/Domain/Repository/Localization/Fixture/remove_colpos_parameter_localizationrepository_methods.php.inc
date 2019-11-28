<?php

use TYPO3\CMS\Backend\Domain\Repository\Localization\LocalizationRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ClassWithOldColPosParameterCall
{
    public function method(): void
    {
        $localizationRepository = GeneralUtility::makeInstance(LocalizationRepository::class);
        $localizationRepository->fetchOriginLanguage(1);
        $localizationRepository->fetchOriginLanguage(1, 2, 3);
        $localizationRepository->getLocalizedRecordCount(1, 2, 3);
        $localizationRepository->fetchOriginLanguage(1, 2, 3);
        $localizationRepository->getRecordsToCopyDatabaseResult(1, 2, 3);
    }
}

?>
-----
<?php

use TYPO3\CMS\Backend\Domain\Repository\Localization\LocalizationRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ClassWithOldColPosParameterCall
{
    public function method(): void
    {
        $localizationRepository = GeneralUtility::makeInstance(LocalizationRepository::class);
        $localizationRepository->fetchOriginLanguage(1);
        $localizationRepository->fetchOriginLanguage(1, 3);
        $localizationRepository->getLocalizedRecordCount(1, 3);
        $localizationRepository->fetchOriginLanguage(1, 3);
        $localizationRepository->getRecordsToCopyDatabaseResult(1, 3);
    }
}

?>
