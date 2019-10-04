<?php

namespace Ssch\TYPO3Rector\Tests\Annotation;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Annotation\CascadeAnnotation;
use Ssch\TYPO3Rector\Annotation\IgnoreValidationAnnotation;
use Ssch\TYPO3Rector\Annotation\InjectAnnotation;
use Ssch\TYPO3Rector\Annotation\LazyAnnotation;
use Ssch\TYPO3Rector\Annotation\TransientAnnotation;

class AnnotationTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideDataForTest()
     *
     * @param string $file
     */
    public function test(string $file): void
    {
        $this->doTestFile($file);
    }

    public function provideDataForTest(): Iterator
    {
        yield [__DIR__ . '/Fixture/inject.php.inc'];
        yield [__DIR__ . '/Fixture/cascade.php.inc'];
        yield [__DIR__ . '/Fixture/ignorevalidation.php.inc'];
        yield [__DIR__ . '/Fixture/lazy.php.inc'];
        yield [__DIR__ . '/Fixture/transient.php.inc'];
    }

    protected function getRectorsWithConfiguration(): array
    {
        return [
            InjectAnnotation::class => [],
            CascadeAnnotation::class => [],
            IgnoreValidationAnnotation::class => [],
            LazyAnnotation::class => [],
            TransientAnnotation::class => [],
        ];
    }
}
