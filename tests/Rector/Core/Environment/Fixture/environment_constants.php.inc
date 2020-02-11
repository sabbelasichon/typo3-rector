<?php

use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\EnvironmentService;

final class ClassWithALotOfEnvironmentVariables
{
    public function method(): void
    {
        PATH_thisScript . 'something';
        PATH_site . 'something';
        TYPO3_OS;
        PATH_typo3conf;
        PATH_typo3;
        TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_CLI;

        if (Bootstrap::usesComposerClassLoading()) {
        }

        if (GeneralUtility::getApplicationContext()) {
        }

        if (EnvironmentService::isEnvironmentInCliMode()) {
        }
    }
}

?>
-----
<?php

use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\EnvironmentService;

final class ClassWithALotOfEnvironmentVariables
{
    public function method(): void
    {
        \TYPO3\CMS\Core\Core\Environment::getCurrentScript() . 'something';
        \TYPO3\CMS\Core\Core\Environment::getPublicPath() . 'something';
        \TYPO3\CMS\Core\Core\Environment::isUnix() || \TYPO3\CMS\Core\Core\Environment::isWindows();
        \TYPO3\CMS\Core\Core\Environment::getLegacyConfigPath();
        \TYPO3\CMS\Core\Core\Environment::getBackendPath();
        \TYPO3\CMS\Core\Core\Environment::isCli() & \TYPO3\CMS\Core\Core\Environment::isCli();

        if (\TYPO3\CMS\Core\Core\Environment::isComposerMode()) {
        }

        if (\TYPO3\CMS\Core\Core\Environment::getContext()) {
        }

        if (\TYPO3\CMS\Core\Core\Environment::isCli()) {
        }
    }
}

?>
