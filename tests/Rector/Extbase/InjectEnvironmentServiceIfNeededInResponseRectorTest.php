<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Extbase;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

final class InjectEnvironmentServiceIfNeededInResponseRectorTest extends AbstractRectorWithConfigTestCase
{
    /**
     * @dataProvider provideDataForTest()
     */
    public function test(string $file): void
    {
        $this->doTestFile($file);
    }

    public function provideDataForTest(): Iterator
    {
        yield [__DIR__ . '/Fixture/inject_environment_service_if_needed.php.inc'];
    }
}
