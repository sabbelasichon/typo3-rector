<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\RemoveTcaDescriptionFromDefaultFieldInformationRector\Fixture;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

class RemoveProperty extends AbstractFormElement
{
    protected $defaultFieldInformation = [
        'tcaDescription' => [
            'renderType' => 'tcaDescription',
        ],
    ];

    public function render(): array
    {
        return [];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\RemoveTcaDescriptionFromDefaultFieldInformationRector\Fixture;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

class RemoveProperty extends AbstractFormElement
{
    public function render(): array
    {
        return [];
    }
}

?>
