<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Charset\CharsetConverter;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Breaking-74031-CharsetConverterParametersRemoved.html
 */
final class RemoveCharsetConverterParametersRector extends AbstractRector
{
    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, CharsetConverter::class)) {
            return null;
        }

        if (! $this->isNames($node->name, ['entities_to_utf8', 'utf8_to_numberarray'])) {
            return null;
        }

        $node->args = [$node->args[0]];

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove CharsetConvertParameters',
            [
                new CodeSample(<<<'PHP'
$charsetConvert = GeneralUtility::makeInstance(CharsetConverter::class);
$charsetConvert->entities_to_utf8('string', false);
$charsetConvert->utf8_to_numberarray('string', false, false);
PHP
                    , <<<'PHP'
$charsetConvert = GeneralUtility::makeInstance(CharsetConverter::class);
$charsetConvert->entities_to_utf8('string');
$charsetConvert->utf8_to_numberarray('string');
PHP
                ),
            ]
        );
    }
}
