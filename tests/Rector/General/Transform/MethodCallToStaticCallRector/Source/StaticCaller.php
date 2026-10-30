<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\General\Transform\MethodCallToStaticCallRector\Source;

final class StaticCaller
{
    public static function anotherMethod(string $arg): string
    {
    }
}
