<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateGetPublicResourceWebPath\Fixture;

use TYPO3\CMS\Core\Utility\PathUtility;

class MyClass
{
    public function renderUrl(string $extResource): string
    {
        $path = PathUtility::getPublicResourceWebPath($extResource);
        return $path;
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateGetPublicResourceWebPath\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\SystemResource\Publishing\SystemResourcePublisherInterface;
use TYPO3\CMS\Core\SystemResource\Publishing\UriGenerationOptions;
use TYPO3\CMS\Core\SystemResource\SystemResourceFactory;

class MyClass
{
    public function __construct(
        private readonly SystemResourceFactory $systemResourceFactory,
        private readonly SystemResourcePublisherInterface $resourcePublisher,
) {}

    public function renderUrl(string $resourceIdentifier, ServerRequestInterface $request): string
    {
        $resource = $this->systemResourceFactory->createPublicResource($resourceIdentifier);
        $path = (string) $this->resourcePublisher->generateUri($resource, $request, new UriGenerationOptions(absoluteUri: true));

        return $path;
    }
}

?>
