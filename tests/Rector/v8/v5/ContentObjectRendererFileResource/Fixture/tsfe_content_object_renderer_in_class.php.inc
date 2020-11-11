<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v5\ContentObjectRendererFileResource\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class MyClass
{
    /**
     * @var string
     */
    private $template = '';

    /**
     * @var ContentObjectRenderer
     */
    private $contentObject;

    public function __construct()
    {
        $this->contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
    }

    public function initialize(): void
    {
        $this->template = $this->contentObject->fileResource('EXT:vendor/Resources/Private/Templates/Template.html');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v5\ContentObjectRendererFileResource\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class MyClass
{
    /**
     * @var string
     */
    private $template = '';

    /**
     * @var ContentObjectRenderer
     */
    private $contentObject;

    public function __construct()
    {
        $this->contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
    }

    public function initialize(): void
    {
        $typoscriptFrontendController = $GLOBALS['TSFE'];
        $path = $typoscriptFrontendController->tmpl->getFileName('EXT:vendor/Resources/Private/Templates/Template.html');
        if ($path !== null && file_exists($path)) {
            $this->template = file_get_contents($path);
        }
    }
}

?>
