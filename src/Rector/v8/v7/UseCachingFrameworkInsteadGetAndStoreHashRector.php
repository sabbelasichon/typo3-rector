<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Expr\Cast\Int_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-80524-PageRepositorygetHashAndPageRepositorystoreHash.html
 */
final class UseCachingFrameworkInsteadGetAndStoreHashRector extends AbstractRector
{
    /**
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use the Caching Framework directly instead of methods PageRepository::getHash and PageRepository::storeHash',
            [new CodeSample(<<<'CODE_SAMPLE'
$GLOBALS['TSFE']->sys_page->storeHash('hash', ['foo', 'bar', 'baz'], 'ident');
$hashContent2 = $GLOBALS['TSFE']->sys_page->getHash('hash');
CODE_SAMPLE
                    , <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_hash')->set('hash', ['foo', 'bar', 'baz'], ['ident_' . 'ident'], 0);
$hashContent = GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_hash')->get('hash');
CODE_SAMPLE
                )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class];
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $this->isNames($node->name, ['storeHash', 'getHash'])) {
            return null;
        }

        if ($this->isName($node->name, 'getHash')) {
            if (! isset($node->args[0])) {
                return null;
            }

            return $this->nodeFactory->createMethodCall($this->createCacheManager(), 'get', [$node->args[0]->value]);
        }

        if (! isset($node->args[0], $node->args[1], $node->args[2])) {
            return null;
        }

        $hash = $node->args[0]->value;
        $data = $node->args[1]->value;
        $ident = $node->args[2]->value;
        $lifetime = isset($node->args[3]) ? new Int_($node->args[3]->value) : new LNumber(0);

        return $this->nodeFactory->createMethodCall($this->createCacheManager(), 'set', [
            $hash,
            $data,
            $this->nodeFactory->createArray([$this->nodeFactory->createConcat([new String_('ident_'), $ident])]),
            $lifetime,
        ]);
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    private function shouldSkip(Node $node): bool
    {
        if ($this->typo3NodeResolver->isMethodCallOnSysPageOfTSFE($node)) {
            return false;
        }
        return ! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(PageRepository::class)
        );
    }

    private function createCacheManager(): MethodCall
    {
        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall(
                GeneralUtility::class,
                'makeInstance',
                [$this->nodeFactory->createClassConstReference(CacheManager::class)]
            ),
            'getCache',
            [new String_('cache_hash')]
        );
    }
}
