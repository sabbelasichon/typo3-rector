<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source;

class ServiceWithConstructorAndScalarType
{
    public function __construct(string $argument)
    {
    }
}
