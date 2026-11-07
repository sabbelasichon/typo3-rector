<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateFormEngineAdditionalHiddenFieldsRector\Fixture;

class NotAFormEngineNode
{
    public function render(): array
    {
        $resultArray = ['html' => ''];
        $resultArray['additionalHiddenFields'][] = '<input type="hidden" name="myField" />';

        return $resultArray;
    }
}

?>
