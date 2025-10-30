<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateTcaTabLabelsRector\Fixture;

return [
    'types' => [
        '1' => ['showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                fileinfo, alternative, description, title, --palette--;;language,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                categories,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        '],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateTcaTabLabelsRector\Fixture;

return [
    'types' => [
        '1' => ['showitem' => '
            --div--;core.form.tabs:general,
                fileinfo, alternative, description, title, --palette--;;language,
            --div--;core.form.tabs:categories,
                categories,
            --div--;core.form.tabs:extended,
        '],
    ],
];

?>
