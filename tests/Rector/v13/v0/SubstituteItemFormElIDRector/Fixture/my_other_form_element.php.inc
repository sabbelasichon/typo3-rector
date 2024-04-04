<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\SubstituteItemFormElIDRector\Fixture;

class MyOtherCustomFormElement
{
    private array $data;

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function render(): array
    {
        $attributeId = htmlspecialchars($this->data['parameterArray']['itemFormElID']);
        return ['<input id="' . $attributeId . '">'];
    }
}

?>
