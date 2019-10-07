<?php

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
    }
}

?>
-----
<?php

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
    }
}

?>
