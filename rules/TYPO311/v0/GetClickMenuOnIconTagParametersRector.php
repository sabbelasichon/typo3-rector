<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Deprecation-92583-DeprecateLastArgumentsOfWrapClickMenuOnIcon.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v0\GetClickMenuOnIconTagParametersRector\GetClickMenuOnIconTagParametersRectorTest
 */
final class GetClickMenuOnIconTagParametersRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Backend\Utility\BackendUtility')
        )) {
            return null;
        }

        if (! $this->isName($node->name, 'wrapClickMenuOnIcon')) {
            return null;
        }

        if (($node->args === []) > 3) {
            return null;
        }

        $returnTagParameters = isset($node->args[6]) ? $this->valueResolver->getValue($node->args[6]->value) : false;

        if ($returnTagParameters === null) {
            return null;
        }

        if ($returnTagParameters === false) {
            unset($node->args[3], $node->args[4], $node->args[5], $node->args[6]);
            return $node;
        }

        return $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Backend\Utility\BackendUtility',
            'getClickMenuOnIconTagParameters',
            [$node->args[0], $node->args[1], $node->args[2]]
        );
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use `BackendUtility::getClickMenuOnIconTagParameters()` instead of `BackendUtility::wrapClickMenuOnIcon()`',
            [new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Utility\BackendUtility;
$returnTagParameters = true;
BackendUtility::wrapClickMenuOnIcon('pages', 1, 'foo', '', '', '', $returnTagParameters);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Utility\BackendUtility;
$returnTagParameters = true;
BackendUtility::getClickMenuOnIconTagParameters('pages', 1, 'foo');
CODE_SAMPLE
            )]
        );
    }
}
