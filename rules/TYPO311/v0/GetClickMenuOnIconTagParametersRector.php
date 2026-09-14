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
        if (! $this->isName($node->name, 'wrapClickMenuOnIcon')) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Backend\Utility\BackendUtility')
        )) {
            return null;
        }

        if (count($node->getArgs()) <= 3) {
            return null;
        }

        if (isset($node->getArgs()[4])) {
            $fifthArgType = $this->getType($node->getArgs()[4]->value);
            if ($fifthArgType->isArray()->yes()) {
                return null;
            }
        }

        $returnTagParameters = isset($node->getArgs()[6]) ? $this->valueResolver->getValue(
            $node->getArgs()[6]
                ->value
        ) : false;
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
            [$node->getArgs()[0], $node->getArgs()[1], $node->getArgs()[2]]
        );
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use `BackendUtility::getClickMenuOnIconTagParameters()` instead of `BackendUtility::wrapClickMenuOnIcon()`',
            [new CodeSample(
                <<<'CODE_SAMPLE'
$returnTagParameters = true;
\TYPO3\CMS\Backend\Utility\BackendUtility::wrapClickMenuOnIcon('pages', 1, 'foo', '', '', '', $returnTagParameters);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$returnTagParameters = true;
\TYPO3\CMS\Backend\Utility\BackendUtility::getClickMenuOnIconTagParameters('pages', 1, 'foo');
CODE_SAMPLE
            )]
        );
    }
}
