<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v2\typo3\SubstituteEnvironmentServiceWithApplicationTypeRector\Source;

final class MyCustomEnvironment
{
    public function isEnvironmentInFrontendMode(): bool
    {
        return false;
    }

    public function isEnvironmentInBackendMode(): bool
    {
        return false;
    }
}
