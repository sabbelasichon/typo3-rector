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
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Deprecation-82899-ExtensionManagementUtilityMethods.html
 */
final class RefactorMethodsFromExtensionManagementUtilityRector extends AbstractRector
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
        $className = $this->getName($node->class);
        $methodName = $this->getName($node->name);
        if (ExtensionManagementUtility::class !== $className) {
            return null;
        }
        switch ($methodName) {
            case 'isLoaded':
                return $this->removeSecondArgumentFromMethodIsLoaded($node);
            case 'siteRelPath':
                return $this->createNewMethodCallForSiteRelPath($node);
            case 'removeCacheFiles':
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
            new CodeSample(<<<'PHP'
ExtensionManagementUtility::removeCacheFiles();
PHP
, <<<'PHP'
GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)->flushCachesInGroup('system');
PHP
),
        ]);
    }

    private function createNewMethodCallForSiteRelPath(StaticCall $node): StaticCall
    {
        $firstArgument = $node->args[0];
        return $this->createStaticCall(
            PathUtility::class,
            'stripPathSitePrefix',
            [$this->createStaticCall(ExtensionManagementUtility::class, 'extPath', [$firstArgument])]
        );
    }

    private function createNewMethodCallForRemoveCacheFiles(): MethodCall
    {
        return $this->createMethodCall(
            $this->createStaticCall(
                GeneralUtility::class,
                'makeInstance',
                [$this->createClassConstReference(CacheManager::class)]
            ),
            'flushCachesInGroup',
            [$this->createArg('system')]
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
