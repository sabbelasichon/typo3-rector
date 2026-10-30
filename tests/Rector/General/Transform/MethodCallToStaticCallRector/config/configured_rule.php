<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Ssch\TYPO3Rector\General\Transform\MethodCallToStaticCallRector;
use Ssch\TYPO3Rector\Tests\Rector\General\Transform\MethodCallToStaticCallRector\Source\AnotherDependency;
use Ssch\TYPO3Rector\Tests\Rector\General\Transform\MethodCallToStaticCallRector\Source\StaticCaller;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->ruleWithConfiguration(MethodCallToStaticCallRector::class, [
        new MethodCallToStaticCall(AnotherDependency::class, 'process', StaticCaller::class, 'anotherMethod'),
    ]);
};
