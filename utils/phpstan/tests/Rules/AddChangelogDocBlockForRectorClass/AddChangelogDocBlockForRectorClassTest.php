<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PHPStan\Tests\Rules\AddChangelogDocBlockForRectorClass;

use Iterator;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Ssch\TYPO3Rector\PHPStan\Rules\AddChangelogDocBlockForRectorClassRule;

/**
 * @extends RuleTestCase<AddChangelogDocBlockForRectorClassRule>
 */
final class AddChangelogDocBlockForRectorClassTest extends RuleTestCase
{
    /**
     * @dataProvider provideData()
     * @param mixed[] $expectedErrorsWithLines
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

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../../config/typo3-rector.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(AddChangelogDocBlockForRectorClassRule::class);
    }
}
