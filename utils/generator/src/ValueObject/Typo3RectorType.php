<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Generator\Contract\Typo3RectorTypeInterface;

final class Typo3RectorType implements Typo3RectorTypeInterface
{
    public function __toString(): string
    {
        return 'typo3';
    }

    public function getRectorClass(): string
    {
        return AbstractRector::class;
    }

    public function getRectorShortClassName(): string
    {
        return 'AbstractRector';
    }
}
