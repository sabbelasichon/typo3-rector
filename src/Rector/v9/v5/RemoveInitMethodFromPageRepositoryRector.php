<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v5;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.5/Deprecation-86338-ChangeVisibilityOfPageRepository-init.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v5\RemoveInitMethodFromPageRepositoryRector\RemoveInitMethodFromPageRepositoryRectorTest
 */
final class RemoveInitMethodFromPageRepositoryRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, Assign::class];
    }

    /**
     * @param MethodCall|Node\Expr\Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        $methodeCall = $node instanceof Assign ? $node->expr : $node;

        if (! $methodeCall instanceof MethodCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodeCall,
            new ObjectType('TYPO3\CMS\Frontend\Page\PageRepository')
        )) {
            return null;
        }

        if (! $this->isName($methodeCall->name, 'init')) {
            return null;
        }

        try {
            $this->removeNode($node);
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            return null;
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove method call init from PageRepository', [new CodeSample(
            <<<'CODE_SAMPLE'
$repository = GeneralUtility::makeInstance(PageRepository::class);
$repository->init(true);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$repository = GeneralUtility::makeInstance(PageRepository::class);
CODE_SAMPLE
        )]);
    }
}
