<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateFormEngineAdditionalHiddenFieldsRector\Fixture;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

class MyOtherFormElement extends AbstractFormElement
{
    public function render(): array
    {
        $resultArray = $this->initializeResultArray();
        $resultArray['additionalHiddenFields'] = array_merge($resultArray['additionalHiddenFields'], ['<input />']);
        $hiddenFields = $resultArray['additionalHiddenFields'];
        $copy = $resultArray['additionalHiddenFields'][] = '<input />';
        $resultArray['otherKey'][] = '<input />';

        return $resultArray;
    }
}

?>
