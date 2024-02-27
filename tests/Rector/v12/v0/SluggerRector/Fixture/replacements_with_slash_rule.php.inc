<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\SluggerRector\Fixture;

return [
    'columns' => [
        'path' => [
            'exclude' => true,
            'label' => 'LLL:EXT:catalog_resources/Resources/Private/Language/Common.xlf:path',
            'config' => [
                'type' => 'slug',
                'generatorOptions' => [
                    'fields' => [
                        'title',
                    ],
                    'prefixParentPageSlug' => false,
                    'replacements' => ['/' => ''],
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInPid',
            ],
        ],
    ],
];

?>
