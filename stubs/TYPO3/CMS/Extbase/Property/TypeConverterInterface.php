<?php

namespace TYPO3\CMS\Extbase\Property;

if (interface_exists('TYPO3\CMS\Extbase\Property\TypeConverterInterface')) {
    return;
}

interface TypeConverterInterface
{
    public function getSupportedSourceTypes(): array;

    public function getSupportedTargetType(): string;

    /**
     * Return the priority of this TypeConverter. TypeConverters with a high priority are chosen before low priority.
     *
     * @return int
     */
    public function getPriority(): int;

    public function canConvertFrom($source, string $targetType): bool;
}
