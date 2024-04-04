<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\SubstituteItemFormElIDRector\Fixture;

use TYPO3\CMS\Backend\Form\NodeInterface;

class MyCustomFormElement implements NodeInterface
{
    private array $data;

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function render(): array
    {
        $foo = $this->data['parameterArray']['foo'];
        $attributeId = htmlspecialchars($this->data['parameterArray']['itemFormElID']);
        return ['<input id="' . $attributeId . '">'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\SubstituteItemFormElIDRector\Fixture;

use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Backend\Form\NodeInterface;

class MyCustomFormElement implements NodeInterface
{
    private array $data;

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function render(): array
    {
        $foo = $this->data['parameterArray']['foo'];
        $attributeId = htmlspecialchars(StringUtility::getUniqueId(self::class . '-'));
        return ['<input id="' . $attributeId . '">'];
    }
}

?>
