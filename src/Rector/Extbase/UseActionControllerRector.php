<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Extbase;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\NodeTypeResolver\Node\AttributeKey;
use TYPO3\CMS\Extbase\Mvc\Controller\AbstractController;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.2/Deprecation-89554-DeprecateTYPO3CMSExtbaseMvcControllerAbstractController.html
 */
final class UseActionControllerRector extends AbstractRector
{
    /**
     * @return string[]
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
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use ActionController class instead of AbstractController if used', [
            new CodeSample(
                <<<'PHP'
class MyController extends AbstractController
{
}
PHP
                ,
                <<<'PHP'
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyController extends ActionController
{
}
PHP
            ),
        ]);
    }
}
