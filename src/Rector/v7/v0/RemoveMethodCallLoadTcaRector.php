<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/7.0/Breaking-61785-LoadTcaFunctionRemoved.html
 */
final class RemoveMethodCallLoadTcaRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, GeneralUtility::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'loadTCA')) {
            return null;
        }

        $this->removeNode($node);

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Remove GeneralUtility::loadTCA() call', [
            new CodeSample('GeneralUtility::loadTCA()', ''),
        ]);
    }
}
