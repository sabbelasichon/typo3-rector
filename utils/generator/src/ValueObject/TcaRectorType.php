<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

use Ssch\TYPO3Rector\Generator\Contract\Typo3RectorTypeInterface;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;

final class TcaRectorType implements Typo3RectorTypeInterface
{
    public function __toString(): string
    {
        return 'tca';
    }

    public function getRectorClass(): string
    {
        return AbstractTcaRector::class;
    }

    public function getRectorShortClassName(): string
    {
        return 'AbstractTcaRector';
    }
}
