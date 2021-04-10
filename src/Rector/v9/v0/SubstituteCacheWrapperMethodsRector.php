<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PHPStan\Type\ObjectType;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Deprecation-83116-CachingFrameworkWrapperMethodsInBackendUtility.html
 */
final class SubstituteCacheWrapperMethodsRector extends AbstractRector
{
    /**
     * @var string
     */
    private const CACHE_ENTRY = 'cacheEntry';

    /**
     * @var string
     */
    private const CACHE_MANAGER = 'cacheManager';

    /**
     * @var string
     */
    private const HASH_CONTENT = 'hashContent';

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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(BackendUtility::class)
        )) {
            return null;
        }

        if (! $this->isNames($node->name, ['getHash', 'storeHash'])) {
            return null;
        }

        if ($this->isName($node->name, 'getHash')) {
            $this->getCacheMethod($node);
        } elseif ($this->isName($node->name, 'storeHash')) {
            $this->setCacheMethod($node);
        }

        // At the end, remove the old method call node
        try {
            $this->removeNode($node);
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
            $this->removeNode($parentNode);
        }

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Caching framework wrapper methods in BackendUtility', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Utility\BackendUtility;
$hash = 'foo';
$content = BackendUtility::getHash($hash);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$hash = 'foo';
$cacheManager = GeneralUtility::makeInstance(CacheManager::class);
$cacheEntry = $cacheManager->getCache('cache_hash')->get($hash);
$hashContent = null;
if ($cacheEntry) {
    $hashContent = $cacheEntry;
}
$content = $hashContent;
CODE_SAMPLE
            ),
        ]);
    }

    private function createCacheManager(): StaticCall
    {
        return $this->nodeFactory->createStaticCall(GeneralUtility::class, 'makeInstance', [
            $this->nodeFactory->createClassConstReference(CacheManager::class),
        ]);
    }

    private function getCacheMethod(StaticCall $node): void
    {
        $this->addCacheManagerNode($node);

        $cacheEntryNode = new Assign(new Variable(self::CACHE_ENTRY), $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall(self::CACHE_MANAGER, 'getCache', ['cache_hash']),
            'get',
            [$node->args[0]]
        ));
        $this->addNodeAfterNode($cacheEntryNode, $node);

        $hashContentNode = new Assign(new Variable(self::HASH_CONTENT), $this->nodeFactory->createNull());
        $this->addNodeAfterNode($hashContentNode, $node);

        $ifNode = new If_(new Variable(self::CACHE_ENTRY));
        $ifNode->stmts[] = new Expression(new Assign(new Variable(self::HASH_CONTENT), new Variable(
            self::CACHE_ENTRY
        )));
        $this->addNodeAfterNode($ifNode, $node);

        $this->addNodeAfterNode(new Assign(new Variable('content'), new Variable(self::HASH_CONTENT)), $node);
    }

    private function addCacheManagerNode(StaticCall $node): void
    {
        $cacheManagerNode = new Assign(new Variable(self::CACHE_MANAGER), $this->createCacheManager());
        $this->addNodeAfterNode($cacheManagerNode, $node);
    }

    private function setCacheMethod(StaticCall $node): void
    {
        $this->addCacheManagerNode($node);

        $arguments = [
            $node->args[0],
            $node->args[1],
            new Array_([new ArrayItem(new Concat(new String_('ident_'), $node->args[2]->value))]),
            $this->nodeFactory->createArg(0),
        ];

        $cacheEntryNode = $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall(self::CACHE_MANAGER, 'getCache', ['cache_hash']),
            'set',
            $arguments
        );
        $this->addNodeAfterNode($cacheEntryNode, $node);
    }
}
