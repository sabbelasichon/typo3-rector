<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v7\v5\UseExtPrefixForTcaIconFile\Fixture;

$array = [
    'ctrl' => [
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('foo').'Resources/Public/Icons/image.png',
    ],
];

$array2 = [
    'ctrl' => [
        'iconfile' => 'EXT:foo/Resources/Public/Icons/image.png',
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v7\v5\UseExtPrefixForTcaIconFile\Fixture;

$array = [
    'ctrl' => [
        'iconfile' => 'EXT:foo/Resources/Public/Icons/image.png',
    ],
];

$array2 = [
    'ctrl' => [
        'iconfile' => 'EXT:foo/Resources/Public/Icons/image.png',
    ],
];

?>
