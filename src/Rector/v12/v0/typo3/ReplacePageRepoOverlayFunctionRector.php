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
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-98303-RemovedHooksForLanguageOverlaysInPageRepository.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplacePageRepoOverlayFunctionRector\ReplacePageRepoOverlayFunctionRectorTest
 */
final class ReplacePageRepoOverlayFunctionRector extends AbstractRector
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $this->isName($node->name, 'getRecordOverlay')) {
            return null;
        }

        return $this->nodeFactory->createMethodCall($node->var, 'getLanguageOverlay', array_slice($node->args, 0, 2));
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace PageRepository->getRecordOverlay() with ->getLanguageOverlay()', [new CodeSample(
            <<<'CODE_SAMPLE'
$pageRepo->getRecordOverlay('', [], '');
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$pageRepo->getLanguageOverlay('', []);
CODE_SAMPLE
        )]);
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        return ! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Frontend\Page\PageRepository')
        );
    }
}
