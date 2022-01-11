<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v3\SubstituteExtbaseRequestGetBaseUriRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction(): void
    {
        $baseUri = $this->request->getBaseUri();
        $this->view->assign('baseUri', $baseUri);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v3\SubstituteExtbaseRequestGetBaseUriRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction(): void
    {
        $request = $GLOBALS['TYPO3_REQUEST'];
        $normalizedParams = $request->getAttribute('normalizedParams');
        $baseUri = $normalizedParams->getSiteUrl();
        $this->view->assign('baseUri', $baseUri);
    }
}

?>
