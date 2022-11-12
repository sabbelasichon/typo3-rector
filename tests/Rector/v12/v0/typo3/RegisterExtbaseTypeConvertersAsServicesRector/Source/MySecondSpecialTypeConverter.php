<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RegisterExtbaseTypeConvertersAsServicesRector\Source;

use TYPO3\CMS\Extbase\Property\TypeConverterInterface;

final class MySecondSpecialTypeConverter implements TypeConverterInterface
{
    public function getSupportedSourceTypes(): array
    {
        return ['array'];
    }

    public function getSupportedTargetType(): string
    {
        return 'int';
    }

    public function getPriority(): int
    {
        return 10;
    }

    public function canConvertFrom($source, string $targetType): bool
    {
        return false;
    }
}
