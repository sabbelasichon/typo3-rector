<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v3;

use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/11.3/Deprecation-94193-PublicUrlWithRelativePathsInFALAPI.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v3\HandlePublicFALUrlsWithRelativePathRector\HandlePublicFALUrlsWithRelativePathRectorTest
 */
final class HandlePublicFALUrlsWithRelativePathRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
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

    private function shouldSkip(Node\Expr\MethodCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Resource\FileInterface')
        )
            &&
            ! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
                $node,
                new ObjectType('TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface')
            )) {
            return true;
        }

        if (! $this->isName($node->name, 'getPublicUrl')) {
            return true;
        }

        return false;
    }
}
