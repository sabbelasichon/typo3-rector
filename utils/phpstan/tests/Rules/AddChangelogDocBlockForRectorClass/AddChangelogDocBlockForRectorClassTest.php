<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PHPStan\Tests\Rules\AddChangelogDocBlockForRectorClass;

use Iterator;
use PHPStan\Rules\Rule;
use Ssch\TYPO3Rector\PHPStan\Rules\AddChangelogDocBlockForRectorClass;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends AbstractServiceAwareRuleTestCase<AddChangelogDocBlockForRectorClass>
 */
final class AddChangelogDocBlockForRectorClassTest extends AbstractServiceAwareRuleTestCase
{
    /**
     * @dataProvider provideData()
     * @param array<string|int> $expectedErrorsWithLines
     */
    public function testRule(string $filePath, array $expectedErrorsWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorsWithLines);
    }

    /**
     * @return Iterator<mixed>
     */
    public function provideData(): Iterator
    {
        $message = sprintf(AddChangelogDocBlockForRectorClass::ERROR_MESSAGE, 'MissingChangelog');
        yield [__DIR__ . '/Fixture/MissingChangelog.php', [[$message, 12]]];
        yield [__DIR__ . '/Fixture/SkipWithChangelog.php', []];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            AddChangelogDocBlockForRectorClass::class,
            __DIR__ . '/../../../config/typo3-rector.neon'
        );
    }
}
