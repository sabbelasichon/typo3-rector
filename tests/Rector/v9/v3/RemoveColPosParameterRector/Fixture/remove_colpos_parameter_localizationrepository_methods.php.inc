<?php

use TYPO3\CMS\Backend\Domain\Repository\Localization\LocalizationRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ClassWithOldColPosParameterCall
{
    public function method(): void
    {
        $localizationRepository = GeneralUtility::makeInstance(LocalizationRepository::class);
        $localizationRepository->fetchOriginLanguage(1);
        $localizationRepository->fetchOriginLanguage(1, 2, 'cs');
        $localizationRepository->getLocalizedRecordCount(1, 2, 'cs');
        $localizationRepository->fetchOriginLanguage(1, 2, 'cs');
        $localizationRepository->getRecordsToCopyDatabaseResult(1, 2, 'cs');
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
        $localizationRepository->fetchOriginLanguage(1, 'cs');
        $localizationRepository->getLocalizedRecordCount(1, 'cs');
        $localizationRepository->fetchOriginLanguage(1, 'cs');
        $localizationRepository->getRecordsToCopyDatabaseResult(1, 'cs');
    }
}

?>
