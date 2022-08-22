<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\UseConfigArrayForTSFEPropertiesRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyFooController
{
    public function myMethod()
    {
        $foo = $GLOBALS['TSFE']->foo;
        $fileTarget = $GLOBALS['TSFE']->fileTarget;
        $intTarget = $this->getTypoScriptFrontendController()->intTarget;
        $extTarget = $this->getTypoScriptFrontendController()->extTarget;
        $spamProtectEmailAddresses = $this->getTypoScriptFrontendController()->spamProtectEmailAddresses;
        $baseUrl = $this->getTypoScriptFrontendController()->baseUrl;
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\UseConfigArrayForTSFEPropertiesRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyFooController
{
    public function myMethod()
    {
        $foo = $GLOBALS['TSFE']->foo;
        $fileTarget = $GLOBALS['TSFE']->config['config']['fileTarget'];
        $intTarget = $this->getTypoScriptFrontendController()->config['config']['intTarget'];
        $extTarget = $this->getTypoScriptFrontendController()->config['config']['extTarget'];
        $spamProtectEmailAddresses = $this->getTypoScriptFrontendController()->config['config']['spamProtectEmailAddresses'];
        $baseUrl = $this->getTypoScriptFrontendController()->config['config']['baseUrl'];
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
