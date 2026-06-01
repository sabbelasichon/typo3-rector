<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector\Fixture;

class NotAHashService
{
    public function caller(string $value): bool
    {
        return $this->validateHmac($value);
    }

    public function validateHmac(string $value): bool
    {
        return $value !== '';
    }
}
