<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typo3;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
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
        return [MethodCall::class];
    }

    /**
     * @param Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\TypoScript\TemplateService')
        )) {
            return null;
        }

        if (! $this->isName($node->name, 'updateRootlineData')) {
            return null;
        }

        $this->nodeRemover->removeNode($node);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
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
