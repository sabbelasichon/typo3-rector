<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector\Source;

class ServiceWithConstructor
{
    private AnInjectedService $injectedService;

    public function __construct(AnInjectedService $injectedService)
    {
        $this->injectedService = $injectedService;
    }

    private function magicHappensHere(): void
    {
        $this->injectedService->doALot();
    }
}
