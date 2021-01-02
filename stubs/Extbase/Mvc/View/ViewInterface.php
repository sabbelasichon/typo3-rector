<?php

namespace TYPO3\CMS\Extbase\Mvc\View;

if(interface_exists(ViewInterface::class)) {
    return;
}

interface ViewInterface
{
    /**
     * @param string $key
     * @param mixed $value
     */
    public function assign(string $key, $value): void;

    public function assignMultiple(array $values): void;

    public function render(): string;
}
