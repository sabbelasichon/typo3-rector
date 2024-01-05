<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\tca\RemoveWorkspacePlaceholderShadowColumnsConfigurationRector\Fixture;

return [
    'ctrl' => [
        'shadowColumnsForNewPlaceholders' => '',
        'shadowColumnsForMovePlaceholders' => '',
    ],
    'columns' => [],
];
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\tca\RemoveWorkspacePlaceholderShadowColumnsConfigurationRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [],
];
?>
