<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\NodeTraverser;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Imaging\GraphicalFunctions;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.4/Deprecation-85978-GraphicalFunctions-init.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v4\RemoveInitMethodGraphicalFunctionsRector\RemoveInitMethodGraphicalFunctionsRectorTest
 */
final class RemoveInitMethodGraphicalFunctionsRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt\Expression::class];
    }

    /**
     * @param Node\Stmt\Expression $node
     */
    public function refactor(Node $node): ?int
    {
        $methodCall = $node->expr;

        if (! $methodCall instanceof MethodCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Core\Imaging\GraphicalFunctions')
        )) {
            return null;
        }

        if (! $this->isName($methodCall->name, 'init')) {
            return null;
        }

        return NodeTraverser::REMOVE_NODE;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove method call init of class GraphicalFunctions', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
$graphicalFunctions->init();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
CODE_SAMPLE
            ),
        ]);
    }
}
