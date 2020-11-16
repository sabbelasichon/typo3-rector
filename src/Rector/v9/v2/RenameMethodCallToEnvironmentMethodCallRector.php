<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v2;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\EnvironmentService;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.2/Feature-84153-IntroduceAGenericEnvironmentClass.html
 */
final class RenameMethodCallToEnvironmentMethodCallRector extends AbstractRector
{
    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Turns method call names to new ones from new Environment API.', [
            new CodeSample(<<<'PHP'
Bootstrap::usesComposerClassLoading();
GeneralUtility::getApplicationContext();
EnvironmentService::isEnvironmentInCliMode();
PHP
, <<<'PHP'
Environment::isComposerMode();
Environment::getContext();
Environment::isCli();
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
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $className = $this->getName($node->class);
        $methodName = $this->getName($node->name);
        if (Bootstrap::class === $className && 'usesComposerClassLoading' === $methodName) {
            return $this->createStaticCall(Environment::class, 'isComposerMode');
        }
        if (GeneralUtility::class === $className && 'getApplicationContext' === $methodName) {
            return $this->createStaticCall(Environment::class, 'getContext');
        }
        if (EnvironmentService::class === $className && 'isEnvironmentInCliMode' === $methodName) {
            return $this->createStaticCall(Environment::class, 'isCli');
        }
        return null;
    }
}
