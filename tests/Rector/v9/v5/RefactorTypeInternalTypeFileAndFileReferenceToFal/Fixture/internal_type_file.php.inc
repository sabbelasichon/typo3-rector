<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v5\RefactorTypeInternalTypeFileAndFileReferenceToFal\Fixture;

final class RefactorTypeInternalTypeFileAndFileReferenceToFal
{
    public function run(): array
    {
        return [
            'ctrl' => [],
            'columns' => [
                'foo' => [
                    'exclude' => 1,
                    'label' => 'FeedbackImage',
                    'config' => [
                        'type' => 'group',
                        'internal_type' => 'file',
                        'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                        'max_size' => '20000',
                        'uploadfolder' => 'fileadmin/feedbacks',
                        'maxitems' => '1',
                    ],
                ],
                'bar' => [
                    'exclude' => 1,
                    'label' => 'FeedbackImage',
                    'config' => [
                        'type' => 'group',
                        'internal_type' => 'file_reference',
                        'max_size' => '20000',
                        'uploadfolder' => 'fileadmin/feedbacks',
                        'maxitems' => '10',
                    ],
                ],
            ],
        ];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v5\RefactorTypeInternalTypeFileAndFileReferenceToFal\Fixture;

final class RefactorTypeInternalTypeFileAndFileReferenceToFal
{
    public function run(): array
    {
        return [
            'ctrl' => [],
            'columns' => [
                'foo' => [
                    'exclude' => 1,
                    'label' => 'FeedbackImage',
                    'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('foo', ['max_size' => '20000', 'uploadfolder' => 'fileadmin/feedbacks', 'maxitems' => '1'], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']),
                ],
                'bar' => [
                    'exclude' => 1,
                    'label' => 'FeedbackImage',
                    'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('bar', ['max_size' => '20000', 'uploadfolder' => 'fileadmin/feedbacks', 'maxitems' => '10']),
                ],
            ],
        ];
    }
}

?>
