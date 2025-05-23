<?php

namespace TYPO3\CMS\Backend\Form;

if (interface_exists('TYPO3\CMS\Backend\Form\NodeInterface')) {
    return;
}

interface NodeInterface
{
    public function setData(array $data): void;

    public function render(): array;
}
