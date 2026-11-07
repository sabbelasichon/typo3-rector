<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\RemoveTcaDescriptionFromDefaultFieldInformationRector\Fixture;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

class SkipOtherFieldInformation extends AbstractFormElement
{
    protected $defaultFieldInformation = [
        'myCustomInfo' => [
            'renderType' => 'myCustomInfo',
        ],
    ];

    public function render(): array
    {
        return [];
    }
}

?>
