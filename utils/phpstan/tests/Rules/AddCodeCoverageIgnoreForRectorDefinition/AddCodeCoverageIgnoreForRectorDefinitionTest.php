<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\PHPStan\Tests\Rules\AddCodeCoverageIgnoreForRectorDefinition;

use Iterator;
use PHPStan\Rules\Rule;
use Ssch\TYPO3Rector\PHPStan\Rules\AddCodeCoverageIgnoreForRectorDefinition;
use Ssch\TYPO3Rector\PHPStan\Tests\Rules\AddCodeCoverageIgnoreForRectorDefinition\Fixture\MissingCodeCoverageIgnore;
use Symplify\PHPStanExtensions\Testing\AbstractServiceAwareRuleTestCase;

final class AddCodeCoverageIgnoreForRectorDefinitionTest extends AbstractServiceAwareRuleTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function testRule(string $filePath, array $expectedErrorsWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorsWithLines);
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    public function provideData(): Iterator
    {
        $message = sprintf(AddCodeCoverageIgnoreForRectorDefinition::ERROR_MESSAGE, MissingCodeCoverageIgnore::class);
        yield [__DIR__ . '/Fixture/MissingCodeCoverageIgnore.php', [[$message, 25]]];
        yield [__DIR__ . '/Fixture/SkipWithCodeCoverageIgnore.php', []];
    }

    protected function getRule(): Rule
    {
        return $this->getRuleFromConfig(
            AddCodeCoverageIgnoreForRectorDefinition::class,
            __DIR__ . '/../../../config/typo3-rector.neon'
        );
    }
}
