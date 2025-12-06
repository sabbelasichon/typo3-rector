<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateManualShortcutButtonCreationRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Template\Components\ComponentFactory;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

class MyClass
{
    private ComponentFactory $componentFactory;
    private ModuleTemplateFactory $moduleTemplateFactory;

    public function __construct(
        ComponentFactory $componentFactory,
        ModuleTemplateFactory $moduleTemplateFactory
    ) {
        $this->componentFactory = $componentFactory;
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    public function mainAction(ServerRequestInterface $request): void
    {
        $view = $this->moduleTemplateFactory->create($request);
        $pageId = 1;
        $shortcutButton = $this->componentFactory->createShortcutButton()
            ->setRouteIdentifier('my_module')
            ->setDisplayName('My Module')
            ->setArguments(['id' => $pageId]);
        $view->addButtonToButtonBar($shortcutButton);
    }

    public function noTitle(ServerRequestInterface $request): void
    {
        $view = $this->moduleTemplateFactory->create($request);
        $pageId = 1;
        $shortcutButton = $this->componentFactory->createShortcutButton()
            ->setRouteIdentifier('my_module')
            ->setArguments(['id' => $pageId]);
        $view->addButtonToButtonBar($shortcutButton);
    }

    public function makeButtons(ServerRequestInterface $request): void
    {
        $view = $this->moduleTemplateFactory->create($request);
        $pageId = 1;
        $shortcutButton = $this->componentFactory->createShortcutButton()
            ->setRouteIdentifier('web_layout')
            ->setDisplayName($this->getShortcutTitle())
            ->setArguments([
                'id' => 'myId',
                'showHidden' => true,
                'function' => 5,
                'language' => 'currentSelectedLanguage',
            ]);
        $foo = 'a';

        $view->addButtonToButtonBar($shortcutButton);
    }

    public function keepLinkButton(ServerRequestInterface $request): void
    {
        $view = $this->moduleTemplateFactory->create($request);
        $clearCacheButton = $this->componentFactory->createLinkButton()
            ->setHref('#')
            ->setDataAttributes(['id' => 8])
            ->setClasses('t3js-clear-page-cache')
            ->setTitle('title')
            ->setIcon(null);
        $view->addButtonToButtonBar($clearCacheButton);
    }

    private function getShortcutTitle(): string
    {
        return 'ShortcutTitle';
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateManualShortcutButtonCreationRector\Fixture;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Template\Components\ComponentFactory;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

class MyClass
{
    private ComponentFactory $componentFactory;
    private ModuleTemplateFactory $moduleTemplateFactory;

    public function __construct(
        ComponentFactory $componentFactory,
        ModuleTemplateFactory $moduleTemplateFactory
    ) {
        $this->componentFactory = $componentFactory;
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    public function mainAction(ServerRequestInterface $request): void
    {
        $view = $this->moduleTemplateFactory->create($request);
        $pageId = 1;
        $view->getDocHeaderComponent()->setShortcutContext('my_module', 'My Module', ['id' => $pageId]);
    }

    public function noTitle(ServerRequestInterface $request): void
    {
        $view = $this->moduleTemplateFactory->create($request);
        $pageId = 1;
        $view->getDocHeaderComponent()->setShortcutContext('my_module', null, ['id' => $pageId]);
    }

    public function makeButtons(ServerRequestInterface $request): void
    {
        $view = $this->moduleTemplateFactory->create($request);
        $pageId = 1;
        $view->getDocHeaderComponent()->setShortcutContext('web_layout', $this->getShortcutTitle(), [
            'id' => 'myId',
            'showHidden' => true,
            'function' => 5,
            'language' => 'currentSelectedLanguage',
        ]);
        $foo = 'a';
    }

    public function keepLinkButton(ServerRequestInterface $request): void
    {
        $view = $this->moduleTemplateFactory->create($request);
        $clearCacheButton = $this->componentFactory->createLinkButton()
            ->setHref('#')
            ->setDataAttributes(['id' => 8])
            ->setClasses('t3js-clear-page-cache')
            ->setTitle('title')
            ->setIcon(null);
        $view->addButtonToButtonBar($clearCacheButton);
    }

    private function getShortcutTitle(): string
    {
        return 'ShortcutTitle';
    }
}
?>
