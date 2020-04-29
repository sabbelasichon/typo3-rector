<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core\Page;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

final class RefactorDeprecatedConcatenateMethodsPageRendererRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/refactor_deprecated_concatenate_methods.php.inc'];
    }
}
