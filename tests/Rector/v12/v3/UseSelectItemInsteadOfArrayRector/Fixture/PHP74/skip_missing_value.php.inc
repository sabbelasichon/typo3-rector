<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\UseSelectItemInsteadOfArrayRector\Fixture\PHP74;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'my_field',
    [
        'my-label',
    ]
);
