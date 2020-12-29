<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PHPStan\Tests\Rules\AddSeeDocBlockForRectorClass;

use Iterator;
use PHPStan\Rules\Rule;
use Ssch\TYPO3Rector\PHPStan\Rules\AddSeeDocBlockForRectorClass;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

final class AddSeeDocBlockForRectorClassTest extends AbstractServiceAwareRuleTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function testRule(string $filePath, array $expectedErrorsWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorsWithLines);
    }

    public function provideData(): Iterator
    {
        $message = sprintf(AddSeeDocBlockForRectorClass::ERROR_MESSAGE, 'MissingSee');
        yield [__DIR__ . '/Fixture/MissingSee.php', [[$message, 12]]];
        yield [__DIR__ . '/Fixture/SkipWithSee.php', []];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            AddSeeDocBlockForRectorClass::class,
            __DIR__ . '/../../../config/typo3-rector.neon'
        );
    }
}
