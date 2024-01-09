<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\RefactorInternalPropertiesOfTSFERector\Fixture;

class CanonicalParametersGetDataHook
{
    public function getDataExtension()
    {
        $cHash = $GLOBALS['TSFE']->cHash;

        $domainStartPage = $GLOBALS['TSFE']->domainStartPage;

        $GLOBALS['TSFE']->cHash = 'fooBarBaz';
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\RefactorInternalPropertiesOfTSFERector\Fixture;

class CanonicalParametersGetDataHook
{
    public function getDataExtension()
    {
        $cHash = $GLOBALS['REQUEST']->getAttribute('routing')->getArguments()['cHash'];

        $domainStartPage = $GLOBALS['REQUEST']->getAttribute('site')->getRootPageId();

        $GLOBALS['REQUEST']->getAttribute('routing')->getArguments()['cHash'] = 'fooBarBaz';
    }
}

?>
