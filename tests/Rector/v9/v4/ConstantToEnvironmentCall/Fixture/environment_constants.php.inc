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

use TYPO3\CMS\Core\Core\Environment;
final class ClassWithALotOfEnvironmentVariables
{
    public function method(): void
    {
        Environment::getCurrentScript() . 'something';
        Environment::getPublicPath() . '/' . 'something';
        Environment::isUnix() || Environment::isWindows();
        Environment::getLegacyConfigPath();
        Environment::getBackendPath();
        Environment::isCli() & Environment::isCli();
    }
}
