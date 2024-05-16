<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-98487-GLOBALSPAGES_TYPESRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\UsePageDoktypeRegistryRector\UsePageDoktypeRegistryRectorTest
 */
final class UsePageDoktypeRegistryRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate from $GLOBALS[\'PAGES_TYPES\'] to the new PageDoktypeRegistry', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$GLOBALS['PAGES_TYPES'][116] = [
    'type' => 'web',
    'allowedTables' => '*',
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\DataHandling\PageDoktypeRegistry;
GeneralUtility::makeInstance(PageDoktypeRegistry::class)->add(116, [
    'type' => 'web',
    'allowedTables' => '*',
]);
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node)
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $node->expr instanceof Array_) {
            return null;
        }

        if (! $node->var instanceof ArrayDimFetch) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall('TYPO3\CMS\Core\Utility\GeneralUtility', 'makeInstance', [
                $this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\DataHandling\PageDoktypeRegistry'),
            ]),
            'add',
            [$node->var->dim, $node->expr]
        );
    }

    private function shouldSkip(Assign $assign): bool
    {
        $arrayDimFetch = $assign->var;
        if (! $arrayDimFetch instanceof ArrayDimFetch) {
            return true;
        }

        if (! $arrayDimFetch->var instanceof ArrayDimFetch) {
            return true;
        }

        $variable = $arrayDimFetch->var->var;

        if (! $variable instanceof Variable) {
            return true;
        }

        if (! $this->isName($variable, 'GLOBALS')) {
            return true;
        }

        $pageTypes = $arrayDimFetch->var->dim;

        if (! $pageTypes instanceof String_) {
            return true;
        }

        return $pageTypes->value !== 'PAGES_TYPES';
    }
}
