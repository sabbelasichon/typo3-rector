<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateTcaTabLabelsRector\Fixture;

return [
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    title, parent,
                --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.items,
                    items,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,--palette--;;timeRestriction,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    description,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            ',
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateTcaTabLabelsRector\Fixture;

return [
    'types' => [
        '1' => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    title, parent,
                --div--;core.form.tabs:items,
                    items,
                --div--;core.form.tabs:language,
                    --palette--;;language,
                --div--;core.form.tabs:access,
                    hidden,--palette--;;timeRestriction,
                --div--;core.form.tabs:notes,
                    description,
                --div--;core.form.tabs:extended,
            ',
        ],
    ],
];

?>
