<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateObsoleteCharsetInSanitizeFileNameRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateObsoleteCharsetInSanitizeFileNameRector\Sources\MyDriver;

class MyClass
{
    public function methodCall(): void
    {
        $driver = new MyDriver();
        $driver->sanitizeFileName('foo.txt', 'utf-8');
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateObsoleteCharsetInSanitizeFileNameRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateObsoleteCharsetInSanitizeFileNameRector\Sources\MyDriver;

class MyClass
{
    public function methodCall(): void
    {
        $driver = new MyDriver();
        $driver->sanitizeFileName('foo.txt');
    }
}
?>
