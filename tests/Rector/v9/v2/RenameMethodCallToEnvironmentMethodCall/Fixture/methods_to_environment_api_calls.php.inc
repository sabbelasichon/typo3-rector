<?php

use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\EnvironmentService;

final class ClassWithALotOfMethodCallsToRefactorToEnvironmentApi
{
    public function method(): void
    {
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

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\EnvironmentService;
final class ClassWithALotOfMethodCallsToRefactorToEnvironmentApi
{
    public function method(): void
    {
        if (Environment::isComposerMode()) {
        }

        if (Environment::getContext()) {
        }

        if (Environment::isCli()) {
        }
    }
}
