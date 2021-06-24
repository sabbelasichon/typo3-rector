<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PHPStan\Tests\Rules\AddCodeCoverageIgnoreForRectorDefinition;

use Iterator;
use PHPStan\Rules\Rule;
use Ssch\TYPO3Rector\PHPStan\Rules\AddCodeCoverageIgnoreForRectorDefinition;
use Ssch\TYPO3Rector\PHPStan\Tests\Rules\AddCodeCoverageIgnoreForRectorDefinition\Fixture\MissingCodeCoverageIgnoreRector;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

/**
 * @extends AbstractServiceAwareRuleTestCase<AddCodeCoverageIgnoreForRectorDefinition>
 */
final class AddCodeCoverageIgnoreForRectorDefinitionTest extends AbstractServiceAwareRuleTestCase
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
        $message = sprintf(
            AddCodeCoverageIgnoreForRectorDefinition::ERROR_MESSAGE,
            MissingCodeCoverageIgnoreRector::class
        );
        yield [__DIR__ . '/Fixture/MissingCodeCoverageIgnoreRector.php', [[$message, 25]]];
        yield [__DIR__ . '/Fixture/SkipWithCodeCoverageIgnoreRector.php', []];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            AddCodeCoverageIgnoreForRectorDefinition::class,
            __DIR__ . '/../../../config/typo3-rector.neon'
        );
    }
}
