<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97312-DeprecateCSH-relatedMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\RemoveAddLLrefForTCAdescrMethodCallRector\RemoveAddLLrefForTCAdescrMethodCallRectorTest
 */
final class RemoveAddLLrefForTCAdescrMethodCallRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove `ExtensionManagementUtility::addLLrefForTCAdescr()` method call',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
ExtensionManagementUtility::addLLrefForTCAdescr('_MOD_web_info', 'EXT:info/Resources/Private/Language/locallang_csh_web_info.xlf');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
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
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node)
    {
        $methodCall = $node->expr;

        if (! $methodCall instanceof StaticCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Core\Utility\ExtensionManagementUtility')
        )) {
            return null;
        }

        if (! $this->isName($methodCall->name, 'addLLrefForTCAdescr')) {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }
}
