<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\RemoveAddQueryStringMethodRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class SomeViewHelper extends AbstractViewHelper
{
    /**
     * @var UriBuilder
     */
    private $uriBuilder;

    public function __construct(UriBuilder $uriBuilder)
    {
        $this->uriBuilder = $uriBuilder;
    }


    public function render()
    {
        $this->uriBuilder->setAddQueryStringMethod('GET');

        $this->uriBuilder->reset();
        $this->uriBuilder->setUseCacheHash(true)
                         ->setCreateAbsoluteUri(true)
                         ->setAddQueryString(true)
                         ->setAddQueryStringMethod('GET')
                         ->build();
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\RemoveAddQueryStringMethodRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class SomeViewHelper extends AbstractViewHelper
{
    /**
     * @var UriBuilder
     */
    private $uriBuilder;

    public function __construct(UriBuilder $uriBuilder)
    {
        $this->uriBuilder = $uriBuilder;
    }


    public function render()
    {
        $this->uriBuilder->reset();
        $this->uriBuilder->setUseCacheHash(true)
                         ->setCreateAbsoluteUri(true)
                         ->setAddQueryString(true)
                         ->build();
    }
}

?>
