<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Frontend\Utility\EidUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Deprecation-81201-EidUtilityinitTCA.html
 */
final class RemoveMethodInitTCARector extends AbstractRector
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
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, EidUtility::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'initTCA')) {
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
        return new RectorDefinition('Remove superfluous EidUtility::initTCA call', [
            new CodeSample(
                <<<'PHP'
use TYPO3\CMS\Frontend\Utility\EidUtility;
EidUtility::initTCA();
PHP
                ,
                <<<'PHP'
PHP
            ),
        ]);
    }
}
