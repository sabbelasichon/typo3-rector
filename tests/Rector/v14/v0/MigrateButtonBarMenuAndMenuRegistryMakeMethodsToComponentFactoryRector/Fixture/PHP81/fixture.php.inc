<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateButtonBarMenuAndMenuRegistryMakeMethodsToComponentFactoryRector\Fixture\PHP81;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplate;

class MyClass
{
    /**
     * @var ModuleTemplate
     */
    protected $moduleTemplate;

    public function myAction(): ResponseInterface
    {
        $url = '';
        $icon = null;

        $buttonBar = $this->moduleTemplate->getDocHeaderComponent()->getButtonBar();

        $genericButton = $buttonBar->makeGenericButton();
        $inputButton = $buttonBar->makeInputButton();
        $splitButton = $buttonBar->makeSplitButton();
        $dropDownButton = $buttonBar->makeDropDownButton();
        $linkButton = $buttonBar->makeLinkButton()->setTitle('My Link');
        $fullyRenderedButton = $buttonBar->makeFullyRenderedButton();
        $shortcutButton = $buttonBar->makeShortcutButton();

        // ButtonBar special case
        $customButton = $buttonBar->makeButton('MyCustomButton');

        // --- MenuRegistry method ---
        $menuRegistry = $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry();
        $menu = $menuRegistry->makeMenu();
        $menuItem = $menu->makeMenuItem()
            ->setTitle('My View')
            ->setHref($url);
        $menu->addMenuItem($menuItem);

        // --- Menu method ---
        $menuItem = $menu->makeMenuItem()->setTitle('My View');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateButtonBarMenuAndMenuRegistryMakeMethodsToComponentFactoryRector\Fixture\PHP81;

use TYPO3\CMS\Backend\Template\Components\ComponentFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplate;

class MyClass
{
    /**
     * @var ModuleTemplate
     */
    protected $moduleTemplate;
    public function __construct(private readonly ComponentFactory $componentFactory)
    {
    }

    public function myAction(): ResponseInterface
    {
        $url = '';
        $icon = null;

        $buttonBar = $this->moduleTemplate->getDocHeaderComponent()->getButtonBar();

        $genericButton = $this->componentFactory->createGenericButton();
        $inputButton = $this->componentFactory->createInputButton();
        $splitButton = $this->componentFactory->createSplitButton();
        $dropDownButton = $this->componentFactory->createDropDownButton();
        $linkButton = $this->componentFactory->createLinkButton()->setTitle('My Link');
        $fullyRenderedButton = $this->componentFactory->createFullyRenderedButton();
        $shortcutButton = $this->componentFactory->createShortcutButton();

        // ButtonBar special case
        $customButton = GeneralUtility::makeInstance('MyCustomButton');

        // --- MenuRegistry method ---
        $menuRegistry = $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry();
        $menu = $this->componentFactory->createMenu();
        $menuItem = $this->componentFactory->createMenuItem()
            ->setTitle('My View')
            ->setHref($url);
        $menu->addMenuItem($menuItem);

        // --- Menu method ---
        $menuItem = $this->componentFactory->createMenuItem()->setTitle('My View');
    }
}

?>
