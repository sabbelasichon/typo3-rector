<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107436-LocalizationParsers.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\ReplaceLocalizationParsersWitHLoaders\ReplaceLocalizationParsersWitHLoadersTest
 */
final class ReplaceLocalizationParsersWitHLoaders extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace localization parsers with loaders',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['SYS']['lang']['parser']['xlf'] = \TYPO3\CMS\Core\Localization\Parser\XliffParser::class;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['LANG']['loader']['xlf'] = \TYPO3\CMS\Core\Localization\Loader\XliffLoader::class;
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): Node
    {
        return $node;
    }
}
