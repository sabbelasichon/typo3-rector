<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePathUtilityGetPublicResourceWebPathRector\Fixture\PHP80;

use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ExtbaseClass extends ActionController
{
    public function renderUrl(string $file): string
    {
        return PathUtility::getPublicResourceWebPath($file);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePathUtilityGetPublicResourceWebPathRector\Fixture\PHP80;

use TYPO3\CMS\Core\SystemResource\SystemResourceFactory;
use TYPO3\CMS\Core\SystemResource\Publishing\SystemResourcePublisherInterface;
use TYPO3\CMS\Core\SystemResource\Publishing\UriGenerationOptions;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ExtbaseClass extends ActionController
{
    /**
     * AbstractController constructor.
     */
    public function __construct(private SystemResourceFactory $systemResourceFactory, private SystemResourcePublisherInterface $resourcePublisher)
    {
        parent::__construct();
    }
    public function renderUrl(string $file): string
    {
        $resource = $this->systemResourceFactory->createPublicResource($file);
        return (string) $this->resourcePublisher->generateUri($resource, $this->request, new UriGenerationOptions(absoluteUri: true));
    }
}

?>
