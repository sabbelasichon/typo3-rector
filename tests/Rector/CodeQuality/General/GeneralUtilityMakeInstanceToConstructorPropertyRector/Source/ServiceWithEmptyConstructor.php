<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source;

class ServiceWithEmptyConstructor
{
    public function __construct()
    {
    }
}
