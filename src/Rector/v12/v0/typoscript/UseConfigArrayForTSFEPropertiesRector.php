<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typoscript;

use Helmich\TypoScriptParser\Parser\AST\Operator\Assignment;
use Helmich\TypoScriptParser\Parser\AST\Scalar;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\AbstractTypoScriptRector;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/12.0/Deprecation-97866-VariousPublicTSFEProperties.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typoscript\UseConfigArrayForTSFEPropertiesRector\UseConfigArrayForTSFEPropertiesRectorTest
 */
final class UseConfigArrayForTSFEPropertiesRector extends AbstractTypoScriptRector
{
    /**
     * @var string[]
     */
    private const DEPRECATED_PUBLIC_PROPERTIES = [
        'intTarget',
        'extTarget',
        'fileTarget',
        'spamProtectEmailAddresses',
        'baseUrl',
    ];

    public function enterNode(Statement $statement): void
    {
        if (! $statement instanceof Assignment) {
            return;
        }

        if (! $statement->value instanceof Scalar) {
            return;
        }

        if (! str_starts_with($statement->value->value, 'TSFE')) {
            return;
        }

        [,$property] = ArrayUtility::trimExplode(':', $statement->value->value, false, 2);

        if (! in_array($property, self::DEPRECATED_PUBLIC_PROPERTIES, true)) {
            return;
        }

        $statement->value->value = sprintf('TSFE:config|config|%s', $property);

        $this->hasChanged = true;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use config array of TSFE instead of properties', [new CodeSample(
            <<<'CODE_SAMPLE'
.data = TSFE:fileTarget
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
.data = TSFE:config|config|fileTarget
CODE_SAMPLE
        )]);
    }
}
