<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateTcaTabLabelsRector\Fixture;

use TYPO3\CMS\Core\Resource\FileType;

return [
    'types' => [
        FileType::UNKNOWN->value => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    fileinfo, title, description, ranking, keywords,
                    --palette--;;20, --palette--;;language,
                --div--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.metadata,
                    creator, creator_tool, publisher, source, copyright,
                    --palette--;;40,
                    status,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            ',
        ],
        FileType::TEXT->value => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    fileinfo, title, description, ranking, keywords,
                    --palette--;;20, --palette--;;language,
                --div--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.metadata,
                    creator, creator_tool, publisher, source, copyright, language,
                    --palette--;;40,
                    status,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            ',
        ],
        FileType::IMAGE->value => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    fileinfo, title, description, ranking, keywords,
                    --palette--;;20, --palette--;;language,
                --div--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.metadata,
                    creator, creator_tool, publisher, source, copyright, language,
                    --palette--;;40,
                    --palette--;;30,
                    --palette--;;60,
                    status,
                --div--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.camera,
                    color_space,
                    --palette--;;50,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            ',
        ],
        FileType::AUDIO->value => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    fileinfo, title, description, ranking, keywords,
                    --palette--;;20, --palette--;;language,
                --div--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.metadata,
                    creator, creator_tool, publisher, source, copyright, language,
                    --palette--;;60,
                    status,
                --div--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.audio,
                    duration,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            ',
        ],
        FileType::VIDEO->value => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    fileinfo, title, description, ranking, keywords,
                    --palette--;;20, --palette--;;language,
                --div--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.metadata,
                    creator, creator_tool, publisher, source, copyright, language,
                    --palette--;;60,
                    status,
                --div--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.video,
                    duration,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            ',
        ],
        FileType::APPLICATION->value => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    fileinfo, title, description, ranking, keywords,
                    --palette--;;20, --palette--;;language,
                --div--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.metadata,
                    creator, creator_tool, publisher, source, copyright, language,
                    --palette--;;40,
                    pages,
                    --palette--;;50,
                    --palette--;;60,
                    status,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            ',
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateTcaTabLabelsRector\Fixture;

use TYPO3\CMS\Core\Resource\FileType;

return [
    'types' => [
        FileType::UNKNOWN->value => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    fileinfo, title, description, ranking, keywords,
                    --palette--;;20, --palette--;;language,
                --div--;core.form.tabs:metadata,
                    creator, creator_tool, publisher, source, copyright,
                    --palette--;;40,
                    status,
                --div--;core.form.tabs:categories,
                    categories,
                --div--;core.form.tabs:extended,
            ',
        ],
        FileType::TEXT->value => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    fileinfo, title, description, ranking, keywords,
                    --palette--;;20, --palette--;;language,
                --div--;core.form.tabs:metadata,
                    creator, creator_tool, publisher, source, copyright, language,
                    --palette--;;40,
                    status,
                --div--;core.form.tabs:categories,
                    categories,
                --div--;core.form.tabs:extended,
            ',
        ],
        FileType::IMAGE->value => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    fileinfo, title, description, ranking, keywords,
                    --palette--;;20, --palette--;;language,
                --div--;core.form.tabs:metadata,
                    creator, creator_tool, publisher, source, copyright, language,
                    --palette--;;40,
                    --palette--;;30,
                    --palette--;;60,
                    status,
                --div--;core.form.tabs:camera,
                    color_space,
                    --palette--;;50,
                --div--;core.form.tabs:categories,
                    categories,
                --div--;core.form.tabs:extended,
            ',
        ],
        FileType::AUDIO->value => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    fileinfo, title, description, ranking, keywords,
                    --palette--;;20, --palette--;;language,
                --div--;core.form.tabs:metadata,
                    creator, creator_tool, publisher, source, copyright, language,
                    --palette--;;60,
                    status,
                --div--;core.form.tabs:audio,
                    duration,
                --div--;core.form.tabs:categories,
                    categories,
                --div--;core.form.tabs:extended,
            ',
        ],
        FileType::VIDEO->value => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    fileinfo, title, description, ranking, keywords,
                    --palette--;;20, --palette--;;language,
                --div--;core.form.tabs:metadata,
                    creator, creator_tool, publisher, source, copyright, language,
                    --palette--;;60,
                    status,
                --div--;core.form.tabs:video,
                    duration,
                --div--;core.form.tabs:categories,
                    categories,
                --div--;core.form.tabs:extended,
            ',
        ],
        FileType::APPLICATION->value => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    fileinfo, title, description, ranking, keywords,
                    --palette--;;20, --palette--;;language,
                --div--;core.form.tabs:metadata,
                    creator, creator_tool, publisher, source, copyright, language,
                    --palette--;;40,
                    pages,
                    --palette--;;50,
                    --palette--;;60,
                    status,
                --div--;core.form.tabs:categories,
                    categories,
                --div--;core.form.tabs:extended,
            ',
        ],
    ],
];

?>
