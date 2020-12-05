<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return [
    'ctrl' => [
        'iconfile' => ExtensionManagementUtility::extRelPath('my_extension') . 'Resources/Public/Icons/image.png'
    ],
    'columns' => [
    ],
];

?>
-----
<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return [
    'ctrl' => [
        'iconfile' => 'EXT:my_extension/Resources/Public/Icons/image.png'
    ],
    'columns' => [
    ],
];

?>
