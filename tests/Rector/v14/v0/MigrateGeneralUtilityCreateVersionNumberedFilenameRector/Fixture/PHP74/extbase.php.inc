<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateGeneralUtilityCreateVersionNumberedFilenameRector\Fixture\PHP74;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ExtbaseClass extends ActionController
{
    public function renderUrl(string $file): string
    {
        $file = GeneralUtility::getFileAbsFileName($file);
        $partialUrl = GeneralUtility::createVersionNumberedFilename($file);
        return PathUtility::getAbsoluteWebPath($partialUrl);
    }

    public function directReturnCreateVersionNumberedFilename(string $file): string
    {
        return GeneralUtility::createVersionNumberedFilename($file);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateGeneralUtilityCreateVersionNumberedFilenameRector\Fixture\PHP74;

use TYPO3\CMS\Core\SystemResource\SystemResourceFactory;
use TYPO3\CMS\Core\SystemResource\Publishing\SystemResourcePublisherInterface;
use TYPO3\CMS\Core\SystemResource\Publishing\UriGenerationOptions;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ExtbaseClass extends ActionController
{
    private SystemResourceFactory $systemResourceFactory;
    private SystemResourcePublisherInterface $resourcePublisher;
    /**
     * AbstractController constructor.
     */
    public function __construct(SystemResourceFactory $systemResourceFactory, SystemResourcePublisherInterface $resourcePublisher)
    {
        parent::__construct();
        $this->systemResourceFactory = $systemResourceFactory;
        $this->resourcePublisher = $resourcePublisher;
    }
    public function renderUrl(string $file): string
    {
        $file = GeneralUtility::getFileAbsFileName($file);
        $resource = $this->systemResourceFactory->createPublicResource($file);
        // TODO from Rector: Remove 'GeneralUtility::getFileAbsFileName()' and 'PathUtility::getAbsoluteWebPath()' yourself.
        // TODO from Rector: See https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107537-CreateVersionNumberedFileName.html#migration
        $partialUrl = (string) $this->resourcePublisher->generateUri($resource, $this->request, new UriGenerationOptions(null, true));
        return PathUtility::getAbsoluteWebPath($partialUrl);
    }

    public function directReturnCreateVersionNumberedFilename(string $file): string
    {
        $resource = $this->systemResourceFactory->createPublicResource($file);
        // TODO from Rector: Remove 'GeneralUtility::getFileAbsFileName()' and 'PathUtility::getAbsoluteWebPath()' yourself.
        // TODO from Rector: See https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107537-CreateVersionNumberedFileName.html#migration
        return (string) $this->resourcePublisher->generateUri($resource, $this->request, new UriGenerationOptions(null, true));
    }
}

?>
