<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Core\Environment;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class RenameMethodCallToEnvironmentMethodCallRector extends AbstractRector
{
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Turns method call names to new ones.', [
            new CodeSample(
                <<<'PHP'
Bootstrap::usesComposerClassLoading();
GeneralUtility::getApplicationContext();
PHP
                ,
                <<<'PHP'
Environment::getContext();
Environment::isComposerMode();
PHP
            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param Node|StaticCall $node
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        $classNode = $node->class;
        $className = $this->getName($classNode);
        $methodName = $this->getName($node);

        if (Bootstrap::class === $className && 'usesComposerClassLoading' === $methodName) {
            return $this->createStaticCall(Environment::class, 'isComposerMode');
        }

        if (GeneralUtility::class === $className && 'getApplicationContext' === $methodName) {
            return $this->createStaticCall(Environment::class, 'getContext');
        }

        return null;
    }
}
