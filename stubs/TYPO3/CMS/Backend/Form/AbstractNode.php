<?php

namespace TYPO3\CMS\Backend\Form;

if (class_exists('TYPO3\CMS\Backend\Form\AbstractNode')) {
    return;
}

abstract class AbstractNode implements NodeInterface
{
    public function setData(array $data): void
    {
    }

    protected function initializeResultArray(): array
    {
        return [];
    }
}
