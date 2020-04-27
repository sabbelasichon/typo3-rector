<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Utility;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.2/Deprecation-89631-UseEnvironmentAPIToFetchApplicationContext.html
 */
final class MoveApplicationContextToEnvironmentApiRector extends AbstractRector
{
    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isMethodStaticCallOrClassMethodObjectType($node, GeneralUtility::class)) {
            return null;
        }

        if (!$this->isName($node->name, 'getApplicationContext')) {
            return null;
        }

        return $this->createStaticCall(Environment::class, 'getContext', []);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use Environment API to fetch application context', [
            new CodeSample(
                <<<'PHP'
GeneralUtility::getApplicationContext();
PHP
                ,
                <<<'PHP'
Environment::getContext();
PHP
            ),
        ]);
    }
}
