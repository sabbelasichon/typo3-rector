<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRootLineRector\Fixture;

class MyIgnoreClass
{
    public function myMethod(): void
    {
        // Should not be migrated
        $other = $GLOBALS['TSFE']->config['config'];
        $root = $GLOBALS['TSFE']->config;
    }
}
