<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\AddMethodToWidgetInterfaceClassesRector\Fixture;

use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;

class MyClass implements WidgetInterface
{
    private readonly array $options;

    public function renderWidgetContent(): string
    {
        return 'foo';
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}

?>
