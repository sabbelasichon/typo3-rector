<?php

namespace TYPO3\CMS\Core\Schema\Field;

if (interface_exists('TYPO3\CMS\Core\Schema\Field\FieldTypeInterface')) {
    return;
}

use TYPO3\CMS\Core\DataHandling\TableColumnType;

interface FieldTypeInterface
{
    public function getType(): string;
    public function isType(TableColumnType ...$columnType): bool;
    public function getName(): string;
    public function getLabel(): string;
    public function supportsAccessControl(): bool;
    public function isRequired(): bool;
    public function isNullable(): bool;
    public function isSearchable(): bool;
    public function getDisplayConditions();
    public function getDefaultValue();
    public function hasDefaultValue(): bool;
    public function getTranslationBehaviour(): FieldTranslationBehaviour;
    public function getConfiguration(): array;
    public static function __set_state(array $state): FieldTypeInterface;
}
