<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.3/Deprecation-94193-PublicUrlWithRelativePathsInFALAPI.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v3\HandlePublicFALUrlsWithRelativePathRector\HandlePublicFALUrlsWithRelativePathRectorTest
 */
final class HandlePublicFALUrlsWithRelativePathRector extends AbstractRector
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

        if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Resource\FileInterface')
        )) {
            $node->args = [];
        }

        if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface')
        )) {
            $node->args = [$node->args[0]];
        }

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Handles public FAL urls with relative paths', [new CodeSample(
            <<<'CODE_SAMPLE'
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
CODE_SAMPLE
        )]);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Resource\FileInterface')
        )
            && ! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
                $node,
                new ObjectType('TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface')
            )) {
            return true;
        }

        return ! $this->isName($node->name, 'getPublicUrl');
    }
}
