<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateAssetCollectorUseNonceOptionToCspRector\Fixture;

use TYPO3\CMS\Core\Page\AssetCollector;

class InjectedProperty
{
    private AssetCollector $assetCollector;

    public function __construct(AssetCollector $assetCollector)
    {
        $this->assetCollector = $assetCollector;
    }

    public function render(): void
    {
        $this->assetCollector->addJavaScript('my-script', 'EXT:my_ext/Resources/Public/JavaScript/foo.js', [], ['useNonce' => true]);
        $this->assetCollector->addStyleSheet(
            'my-style',
            'EXT:my_ext/Resources/Public/Css/foo.css',
            ['media' => 'all'],
            ['priority' => true, 'useNonce' => false]
        );
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateAssetCollectorUseNonceOptionToCspRector\Fixture;

use TYPO3\CMS\Core\Page\AssetCollector;

class InjectedProperty
{
    private AssetCollector $assetCollector;

    public function __construct(AssetCollector $assetCollector)
    {
        $this->assetCollector = $assetCollector;
    }

    public function render(): void
    {
        $this->assetCollector->addJavaScript('my-script', 'EXT:my_ext/Resources/Public/JavaScript/foo.js', [], ['csp' => true]);
        $this->assetCollector->addStyleSheet(
            'my-style',
            'EXT:my_ext/Resources/Public/Css/foo.css',
            ['media' => 'all'],
            ['priority' => true, 'csp' => false]
        );
    }
}

?>
