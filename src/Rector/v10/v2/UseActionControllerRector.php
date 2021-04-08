<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v2;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Extbase\Mvc\Controller\AbstractController;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.2/Deprecation-89554-DeprecateTYPO3CMSExtbaseMvcControllerAbstractController.html
 */
final class UseActionControllerRector extends AbstractRector
{
    /**
     * @return array<class-string<\PhpParser\Node>>
     */

    /**
     * @return array<class-string<\PhpParser\Node>>
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
        $parentClassName = $node->getAttribute(AttributeKey::PARENT_CLASS_NAME);
        if (AbstractController::class !== $parentClassName) {
            return null;
        }
        /** @var string|null $className */
        $className = $node->getAttribute(AttributeKey::CLASS_NAME);
        if (null === $className) {
            return null;
        }
        $node->extends = new FullyQualified(ActionController::class);
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
