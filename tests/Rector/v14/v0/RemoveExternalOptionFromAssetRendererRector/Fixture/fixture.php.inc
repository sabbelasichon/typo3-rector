<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveExternalOptionFromAssetRendererRector\Fixture;

use TYPO3\CMS\Core\Page\AssetCollector;

class MyClass
{
    private AssetCollector $assetCollector;

    public function __construct(AssetCollector $assetCollector)
    {
        $this->assetCollector = $assetCollector;
    }

    public function addExternalStyleSheet(): void
    {
        $this->assetCollector->addStyleSheet(
            'myCssFile',
            '/styles/main.css',
            [],
            ['external' => true]
        );
    }

    public function addAsyncStyleSheet(): void
    {
        $this->assetCollector->addStyleSheet(
            'myCssFile',
            '/styles/main.css',
            [],
            ['external' => true, 'async' => 'async']
        );
    }

    public function addExternalJavaScript(): void
    {
        $this->assetCollector->addJavaScript(
            'myCssFile',
            '/styles/main.css',
            [],
            ['external' => true]
        );
    }

    public function addAsyncJavaScript(): void
    {
        $this->assetCollector->addJavaScript(
            'myCssFile',
            '/styles/main.css',
            [],
            ['external' => true, 'async' => 'async']
        );
    }

    public function removeEmptyArguments(): void
    {
        $this->assetCollector->addStyleSheet(
            'myCssFile',
            'https://example.com/styles/main.css',
            [''],
            []
        );
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveExternalOptionFromAssetRendererRector\Fixture;

use TYPO3\CMS\Core\Page\AssetCollector;

class MyClass
{
    private AssetCollector $assetCollector;

    public function __construct(AssetCollector $assetCollector)
    {
        $this->assetCollector = $assetCollector;
    }

    public function addExternalStyleSheet(): void
    {
        $this->assetCollector->addStyleSheet(
            'myCssFile',
            'URI:/styles/main.css'
        );
    }

    public function addAsyncStyleSheet(): void
    {
        $this->assetCollector->addStyleSheet(
            'myCssFile',
            'URI:/styles/main.css',
            [],
            ['async' => 'async']
        );
    }

    public function addExternalJavaScript(): void
    {
        $this->assetCollector->addJavaScript(
            'myCssFile',
            'URI:/styles/main.css'
        );
    }

    public function addAsyncJavaScript(): void
    {
        $this->assetCollector->addJavaScript(
            'myCssFile',
            'URI:/styles/main.css',
            [],
            ['async' => 'async']
        );
    }

    public function removeEmptyArguments(): void
    {
        $this->assetCollector->addStyleSheet(
            'myCssFile',
            'https://example.com/styles/main.css',
            ['']
        );
    }
}

?>
