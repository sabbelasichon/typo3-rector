<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Deprecation-82899-ExtensionManagementUtilityMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v0\RefactorMethodsFromExtensionManagementUtilityRector\RefactorMethodsFromExtensionManagementUtilityRectorTest
 */
final class RefactorMethodsFromExtensionManagementUtilityRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
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
        if (ExtensionManagementUtility::class !== $className) {
            return null;
        }

        if (null === $methodName) {
            return null;
        }

        if ('isLoaded' === $methodName) {
            return $this->removeSecondArgumentFromMethodIsLoaded($node);
        }

        if ('siteRelPath' === $methodName) {
            return $this->createNewMethodCallForSiteRelPath($node);
        }

        if ('removeCacheFiles' === $methodName) {
            return $this->createNewMethodCallForRemoveCacheFiles();
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor deprecated methods from ExtensionManagementUtility.', [
            new CodeSample(<<<'CODE_SAMPLE'
ExtensionManagementUtility::removeCacheFiles();
CODE_SAMPLE
, <<<'CODE_SAMPLE'
GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)->flushCachesInGroup('system');
CODE_SAMPLE
),
        ]);
    }

    private function createNewMethodCallForSiteRelPath(StaticCall $node): StaticCall
    {
        $firstArgument = $node->args[0];
        return $this->nodeFactory->createStaticCall(
            PathUtility::class,
            'stripPathSitePrefix',
            [$this->nodeFactory->createStaticCall(ExtensionManagementUtility::class, 'extPath', [$firstArgument])]
        );
    }

    private function createNewMethodCallForRemoveCacheFiles(): MethodCall
    {
        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall(
                GeneralUtility::class,
                'makeInstance',
                [$this->nodeFactory->createClassConstReference(CacheManager::class)]
            ),
            'flushCachesInGroup',
            [$this->nodeFactory->createArg('system')]
        );
    }

    private function removeSecondArgumentFromMethodIsLoaded(StaticCall $node): Node
    {
        $numberOfArguments = count($node->args);
        if ($numberOfArguments > 1) {
            unset($node->args[1]);
        }
        return $node;
    }
}
