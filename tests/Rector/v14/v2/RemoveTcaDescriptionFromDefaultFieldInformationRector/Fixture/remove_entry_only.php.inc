<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\RemoveTcaDescriptionFromDefaultFieldInformationRector\Fixture;

use TYPO3\CMS\Backend\Form\Container\AbstractContainer;

class RemoveEntryOnly extends AbstractContainer
{
    protected $defaultFieldInformation = [
        'tcaDescription' => [
            'renderType' => 'tcaDescription',
        ],
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
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\RemoveTcaDescriptionFromDefaultFieldInformationRector\Fixture;

use TYPO3\CMS\Backend\Form\Container\AbstractContainer;

class RemoveEntryOnly extends AbstractContainer
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
