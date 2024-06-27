<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96351-UnusedTemplateService-updateRootlineDataMethodRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveUpdateRootlineDataRector\RemoveUpdateRootlineDataRectorTest
 */
final class RemoveUpdateRootlineDataRector extends AbstractRector
{
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
    public function refactor(Node $node): ?int
    {
        $methodCall = $node->expr;

        if (! $methodCall instanceof MethodCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Core\TypoScript\TemplateService')
        )) {
            return null;
        }

        if (! $this->isName($methodCall->name, 'updateRootlineData')) {
            return null;
        }

        return NodeTraverser::REMOVE_NODE;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove unused TemplateService->updateRootlineData() calls', [new CodeSample(
            <<<'CODE_SAMPLE'
$templateService = GeneralUtility::makeInstance(TemplateService::class);
$templateService->updateRootlineData();
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$templateService = GeneralUtility::makeInstance(TemplateService::class);
CODE_SAMPLE
        )]);
    }
}
