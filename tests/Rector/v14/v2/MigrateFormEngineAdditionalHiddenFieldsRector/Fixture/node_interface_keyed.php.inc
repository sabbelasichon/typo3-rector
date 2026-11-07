<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateFormEngineAdditionalHiddenFieldsRector\Fixture;

use TYPO3\CMS\Backend\Form\NodeInterface;

class MyKeyedNode implements NodeInterface
{
    public function setData(array $data): void
    {
    }

    public function render(): array
    {
        $resultArray = ['html' => ''];
        $resultArray['additionalHiddenFields']['myField'] = '<input type="hidden" name="myField" />';

        return $resultArray;
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateFormEngineAdditionalHiddenFieldsRector\Fixture;

use TYPO3\CMS\Backend\Form\NodeInterface;

class MyKeyedNode implements NodeInterface
{
    public function setData(array $data): void
    {
    }

    public function render(): array
    {
        $resultArray = ['html' => ''];
        $resultArray['html'] .= '<input type="hidden" name="myField" />';

        return $resultArray;
    }
}

?>
