<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PHPStan\Tests\Rules\AddChangelogDocBlockForRectorClass;

use Iterator;
use PHPStan\Rules\Rule;
use Ssch\TYPO3Rector\PHPStan\Rules\AddChangelogDocBlockForRectorClassRule;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends AbstractServiceAwareRuleTestCase<AddChangelogDocBlockForRectorClassRule>
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
        $message = sprintf(AddChangelogDocBlockForRectorClassRule::ERROR_MESSAGE, 'MissingChangelog');
        yield [__DIR__ . '/Fixture/MissingChangelog.php', [[$message, 12]]];
        yield [__DIR__ . '/Fixture/SkipWithChangelog.php', []];
    }

    /**
     * @return AddChangelogDocBlockForRectorClassRule
     */
    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            AddChangelogDocBlockForRectorClassRule::class,
            __DIR__ . '/../../../config/typo3-rector.neon'
        );
    }
}
