<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Cache\CacheManager;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Deprecation-88366-DefaultCachingFrameworkCacheNamesChanged.html
 */
final class ChangeDefaultCachingFrameworkNamesRector extends AbstractRector
{
    /*
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, CacheManager::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'getCache')) {
            return null;
        }

        $argument = $this->getValue($node->args[0]->value);

        if (null === $argument) {
            return null;
        }

        if (! in_array($argument, [
            'cache_core',
            'cache_hash',
            'cache_pages',
            'cache_pagesection',
            'cache_runtime',
            'cache_rootline',
            'cache_imagesizes',
        ], true)) {
            return null;
        }

        $node->args[0] = $this->createArg(str_replace('cache_', '', $argument));

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use new default cache names like core instead of cache_core)', [
            new CodeSample(<<<'PHP'
$cacheManager = GeneralUtility::makeInstance(CacheManager::class);
$cacheManager->getCache('cache_core');
$cacheManager->getCache('cache_hash');
$cacheManager->getCache('cache_pages');
$cacheManager->getCache('cache_pagesection');
$cacheManager->getCache('cache_runtime');
$cacheManager->getCache('cache_rootline');
$cacheManager->getCache('cache_imagesizes');
PHP
                , <<<'PHP'
$cacheManager = GeneralUtility::makeInstance(CacheManager::class);
$cacheManager->getCache('core');
$cacheManager->getCache('hash');
$cacheManager->getCache('pages');
$cacheManager->getCache('pagesection');
$cacheManager->getCache('runtime');
$cacheManager->getCache('rootline');
$cacheManager->getCache('imagesizes');
PHP
            ),
        ]);
    }
}
