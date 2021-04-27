<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v2;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.2/Deprecation-89554-DeprecateTYPO3CMSExtbaseMvcControllerAbstractController.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v2\UseActionControllerRector\UseActionControllerRectorTest
 */
final class UseActionControllerRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (null === $node->extends) {
            return null;
        }

        if (! $this->isName($node->extends, 'TYPO3\CMS\Extbase\Mvc\Controller\AbstractController')) {
            return null;
        }

        $node->extends = new FullyQualified('TYPO3\CMS\Extbase\Mvc\Controller\ActionController');
        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use ActionController class instead of AbstractController if used', [
            new CodeSample(<<<'CODE_SAMPLE'
class MyController extends AbstractController
{
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyController extends ActionController
{
}
CODE_SAMPLE
),
        ]);
    }
}
