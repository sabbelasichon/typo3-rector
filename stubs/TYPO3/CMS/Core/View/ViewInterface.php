<?php

namespace TYPO3\CMS\Core\View;

if (interface_exists('TYPO3\CMS\Core\View\ViewInterface')) {
    return;
}

interface ViewInterface
{
    /**
     * @param string $key
     * @param mixed $value
     */
    public function assign(string $key, $value): self;

    /**
     * Add multiple variables to the view data collection.
     *
     * @param array<string, mixed> $values Array of string keys with mixed-type values.
     */
    public function assignMultiple(array $values): self;

    /**
     * Renders the view. Optionally receives a template location.
     */
    public function render(string $templateFileName = ''): string;
}
