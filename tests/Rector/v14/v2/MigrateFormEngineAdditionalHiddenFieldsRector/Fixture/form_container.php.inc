<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateFormEngineAdditionalHiddenFieldsRector\Fixture;

use TYPO3\CMS\Backend\Form\Container\AbstractContainer;

class MyContainer extends AbstractContainer
{
    public function render(): array
    {
        $result = $this->initializeResultArray();
        foreach (['a', 'b'] as $name) {
            $result['additionalHiddenFields'][] = '<input type="hidden" name="' . $name . '" />';
        }

        return $result;
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateFormEngineAdditionalHiddenFieldsRector\Fixture;

use TYPO3\CMS\Backend\Form\Container\AbstractContainer;

class MyContainer extends AbstractContainer
{
    public function render(): array
    {
        $result = $this->initializeResultArray();
        foreach (['a', 'b'] as $name) {
            $result['html'] .= '<input type="hidden" name="' . $name . '" />';
        }

        return $result;
    }
}

?>
