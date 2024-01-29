<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PHPStan\Tests\Rules\AddCodeCoverageIgnoreForRectorDefinition;

use Iterator;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Ssch\TYPO3Rector\PHPStan\Rules\AddCodeCoverageIgnoreForRectorDefinitionRule;
use Ssch\TYPO3Rector\PHPStan\Tests\Rules\AddCodeCoverageIgnoreForRectorDefinition\Fixture\MissingCodeCoverageIgnore;

/**
 * @extends RuleTestCase<AddCodeCoverageIgnoreForRectorDefinitionRule>
 */
final class AddCodeCoverageIgnoreForRectorDefinitionTest extends RuleTestCase
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
    public static function provideData(): Iterator
    {
        $message = sprintf(
            AddCodeCoverageIgnoreForRectorDefinitionRule::ERROR_MESSAGE,
            MissingCodeCoverageIgnore::class
        );
        yield [__DIR__ . '/Fixture/MissingCodeCoverageIgnore.php', [[$message, 27]]];
        yield [__DIR__ . '/Fixture/SkipWithCodeCoverageIgnore.php', []];
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../../config/typo3-rector.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(AddCodeCoverageIgnoreForRectorDefinitionRule::class);
    }
}
