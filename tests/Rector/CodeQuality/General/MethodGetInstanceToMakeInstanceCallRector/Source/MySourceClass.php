<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MethodGetInstanceToMakeInstanceCallRector\Source;

final class MySourceClass
{
    public static function getInstance(): self
    {
        return new self();
    }
}
