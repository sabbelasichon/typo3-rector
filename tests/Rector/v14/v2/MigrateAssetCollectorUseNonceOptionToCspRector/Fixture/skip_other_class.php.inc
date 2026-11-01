<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateAssetCollectorUseNonceOptionToCspRector\Fixture;

final class SomeOtherCollector
{
    public function addJavaScript(string $identifier, string $source, array $attributes = [], array $options = []): void
    {
    }
}

(new SomeOtherCollector())->addJavaScript('my-script', 'foo.js', [], ['useNonce' => true]);
