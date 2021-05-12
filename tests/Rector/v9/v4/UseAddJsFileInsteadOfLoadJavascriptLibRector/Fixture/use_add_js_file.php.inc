<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseAddJsFileInsteadOfLoadJavascriptLibRector\Fixture;

use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    /**
     * @var ModuleTemplate
     */
    private $moduleTemplate;

    public function __construct()
    {
        $this->moduleTemplate = GeneralUtility::makeInstance(ModuleTemplate::class);
        $this->moduleTemplate->loadJavascriptLib('sysext/backend/Resources/Public/JavaScript/md5.js');
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseAddJsFileInsteadOfLoadJavascriptLibRector\Fixture;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    /**
     * @var ModuleTemplate
     */
    private $moduleTemplate;

    public function __construct()
    {
        $this->moduleTemplate = GeneralUtility::makeInstance(ModuleTemplate::class);
        GeneralUtility::makeInstance(PageRenderer::class)->addJsFile('sysext/backend/Resources/Public/JavaScript/md5.js');
    }
}

?>
