<?php
declare(strict_types=1);

namespace TYPO3\CMS\Dashboard\Widgets;

if (interface_exists('TYPO3\CMS\Dashboard\Widgets\WidgetInterface')) {
    return;
}

interface WidgetInterface
{
    public function renderWidgetContent(): string;

    public function getOptions(): array;
}
