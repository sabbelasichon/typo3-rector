<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyDirectAccessClass
{
    public function myMethod(TypoScriptFrontendController $tsfe): void
    {
        $config = $tsfe->config['config'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyDirectAccessClass
{
    public function myMethod(TypoScriptFrontendController $tsfe): void
    {
        $config = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.typoscript')->getConfigArray();
    }
}

?>
