<?php

namespace TYPO3\CMS\Install\Updates;

if (class_exists('TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate')) {
    return;
}

abstract class AbstractListTypeToCTypeUpdate
{
    /**
     * This must return an array containing the "list_type" to "CType" mapping
     *
     *  Example:
     *
     *  [
     *      'pi_plugin1' => 'pi_plugin1',
     *      'pi_plugin2' => 'new_content_element',
     *  ]
     *
     * @return array<string, string>
     */
    abstract protected function getListTypeToCTypeMapping(): array;

    abstract public function getTitle(): string;

    abstract public function getDescription(): string;
}
