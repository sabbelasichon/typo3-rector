<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v3\typo3\UseNormalizedParamsToGetRequestUrlRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction()
    {
        $requestUri = $this->request->getRequestUri();
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v3\typo3\UseNormalizedParamsToGetRequestUrlRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function myAction()
    {
        $requestUri = $this->request->getAttribute('normalizedParams')->getRequestUrl();
    }
}

?>
