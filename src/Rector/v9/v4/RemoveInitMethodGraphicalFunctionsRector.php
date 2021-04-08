<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Imaging\GraphicalFunctions;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85978-GraphicalFunctions-init.html
 */
final class RemoveInitMethodGraphicalFunctionsRector extends AbstractRector
{
    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, GraphicalFunctions::class)) {
            return null;
        }
        if (! $this->isName($node->name, 'init')) {
            return null;
        }

        $this->removeNode($node);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove method call init of class GraphicalFunctions', [
            new CodeSample(<<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
$graphicalFunctions->init();
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
CODE_SAMPLE
            ),
        ]);
    }
}
