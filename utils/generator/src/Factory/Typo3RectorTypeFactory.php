<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\Factory;

use Ssch\TYPO3Rector\Generator\Contract\Typo3RectorTypeInterface;
use Ssch\TYPO3Rector\Generator\ValueObject\TcaRectorType;
use Ssch\TYPO3Rector\Generator\ValueObject\Typo3RectorType;

final class Typo3RectorTypeFactory
{
    public static function fromString(string $type): Typo3RectorTypeInterface
    {
        if ($type === 'tca') {
            return new TcaRectorType();
        }

        return new Typo3RectorType();
    }
}
