<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRector\Fixture;

class MyIgnoreClass
{
    public function myMethod(): void
    {
        // Should not be migrated
        $other = $GLOBALS['TSFE']->config['other'];
        $root = $GLOBALS['TSFE']->config;
    }
}
