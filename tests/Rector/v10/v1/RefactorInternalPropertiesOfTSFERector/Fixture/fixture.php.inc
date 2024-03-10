<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\RefactorInternalPropertiesOfTSFERector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class CanonicalParametersGetDataHook
{
    public function getDataExtension()
    {
        $cHash = $GLOBALS['TSFE']->cHash;

        $domainStartPage = $GLOBALS['TSFE']->domainStartPage;

        $GLOBALS['TSFE']->cHash = 'fooBarBaz';
    }

    public function testWithReference(): void
    {
        $cHash = $this->getTypoScriptFrontendController()->cHash;

        $domainStartPage = $this->getTypoScriptFrontendController()->domainStartPage;

        $this->getTypoScriptFrontendController()->cHash = 'fooBarBaz';
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\RefactorInternalPropertiesOfTSFERector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class CanonicalParametersGetDataHook
{
    public function getDataExtension()
    {
        $cHash = $GLOBALS['REQUEST']->getAttribute('routing')->getArguments()['cHash'];

        $domainStartPage = $GLOBALS['REQUEST']->getAttribute('site')->getRootPageId();

        $GLOBALS['REQUEST']->getAttribute('routing')->getArguments()['cHash'] = 'fooBarBaz';
    }

    public function testWithReference(): void
    {
        $cHash = $GLOBALS['REQUEST']->getAttribute('routing')->getArguments()['cHash'];

        $domainStartPage = $GLOBALS['REQUEST']->getAttribute('site')->getRootPageId();

        $GLOBALS['REQUEST']->getAttribute('routing')->getArguments()['cHash'] = 'fooBarBaz';
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
