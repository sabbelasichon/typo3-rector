<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateFormEngineAdditionalHiddenFieldsRector\Fixture;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

class MyFormElement extends AbstractFormElement
{
    public function render(): array
    {
        $resultArray = $this->initializeResultArray();
        $resultArray['additionalHiddenFields'][] = '<input type="hidden" name="myField" value="myValue" />';
        $resultArray['html'] = '<div>content</div>';

        return $resultArray;
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateFormEngineAdditionalHiddenFieldsRector\Fixture;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

class MyFormElement extends AbstractFormElement
{
    public function render(): array
    {
        $resultArray = $this->initializeResultArray();
        $resultArray['html'] .= '<input type="hidden" name="myField" value="myValue" />';
        $resultArray['html'] = '<div>content</div>';

        return $resultArray;
    }
}

?>
