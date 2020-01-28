<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Utility;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Core\CacheManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

final class RefactorMethodsFromExtensionManagementUtilityRector extends AbstractRector
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

        if (ExtensionManagementUtility::class !== $className) {
            return null;
        }

        switch ($methodName) {
            case 'isLoaded':
                return $this->removeSecondArgumentFromMethodIsLoaded($node);
                break;
            case 'siteRelPath':
                return $this->createNewMethodCallForSiteRelPath($node);
                break;
            case 'removeCacheFiles':
                return $this->createNewMethodCallForRemoveCacheFiles();
                break;
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Refactor deprecated methods from ExtensionManagementUtility.', [
            new CodeSample(
                <<<'PHP'
ExtensionManagementUtility::removeCacheFiles();
PHP
                ,
                <<<'PHP'
GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)->flushCachesInGroup('system');
PHP
            ),
        ]);
    }

    /**
     * @param Node $node
     *
     * @return StaticCall
     */
    private function createNewMethodCallForSiteRelPath(Node $node): StaticCall
    {
        $firstArgument = $node->args[0];

        return $this->createStaticCall(PathUtility::class, 'stripPathSitePrefix', [$this->createStaticCall(ExtensionManagementUtility::class, 'extPath', [$firstArgument])]);
    }

    /**
     * @return Node\Expr\MethodCall
     */
    private function createNewMethodCallForRemoveCacheFiles(): Node\Expr\MethodCall
    {
        return $this->createMethodCall($this->createStaticCall(
            GeneralUtility::class,
            'makeInstance',
            [
                $this->createClassConstant(CacheManager::class, 'class'),
            ]
        ), 'flushCachesInGroup', [$this->createArg('system')]);
    }

    private function removeSecondArgumentFromMethodIsLoaded(Node $node): Node
    {
        $numberOfArguments = count($node->args);
        if ($numberOfArguments > 1) {
            unset($node->args[1]);
        }

        return $node;
    }
}
