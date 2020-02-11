<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Utility;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Core\Environment;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.2/Deprecation-89631-UseEnvironmentAPIToFetchApplicationContext.html
 */
final class MoveApplicationContextToEnvironmentApiRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @inheritDoc
     */
    public function refactor(Node $node): ?Node
    {
        $classNode = $node->class;
        $className = $this->getName($classNode);
        $methodName = $this->getName($node);

        if (GeneralUtility::class !== $className) {
            return null;
        }

        if ($methodName === 'getApplicationContext') {
            return $this->createStaticCall(Environment::class, 'getContext', []);
        }

        return null;
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
