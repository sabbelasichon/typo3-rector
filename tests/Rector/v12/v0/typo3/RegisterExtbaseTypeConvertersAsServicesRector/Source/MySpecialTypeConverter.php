<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RegisterExtbaseTypeConvertersAsServicesRector\Source;

use TYPO3\CMS\Extbase\Property\TypeConverterInterface;

final class MySpecialTypeConverter implements TypeConverterInterface
{
    public function getSupportedSourceTypes(): array
    {
        return ['int', 'string'];
    }

    public function getSupportedTargetType(): string
    {
        return 'string';
    }

    public function getPriority(): int
    {
        return 70;
    }

    public function canConvertFrom($source, string $targetType): bool
    {
        return false;
    }
}
