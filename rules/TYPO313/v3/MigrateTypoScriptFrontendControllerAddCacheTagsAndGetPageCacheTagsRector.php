<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v3;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.3/Deprecation-102422-TypoScriptFrontendController-addCacheTags.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateTypoScriptFrontendControllerAddCacheTagsAndGetPageCacheTagsRector\MigrateTypoScriptFrontendControllerAddCacheTagsAndGetPageCacheTagsRectorTest
 */
final class MigrateTypoScriptFrontendControllerAddCacheTagsAndGetPageCacheTagsRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    /**
     * @readonly
     */
    private Typo3GlobalsFactory $typo3GlobalsFactory;

    public function __construct(Typo3NodeResolver $typo3NodeResolver, Typo3GlobalsFactory $typo3GlobalsFactory)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
        $this->typo3GlobalsFactory = $typo3GlobalsFactory;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate TypoScriptFrontendController->addCacheTags() and ->getPageCacheTags()', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$GLOBALS['TSFE']->addCacheTags([
    'tx_myextension_mytable_123',
    'tx_myextension_mytable_456'
]);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Cache\CacheTag;

$GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.cache.collector')->addCacheTags(
    new CacheTag('tx_myextension_mytable_123', 3600),
    new CacheTag('tx_myextension_mytable_456', 3600)
);
CODE_SAMPLE
            ),
            new CodeSample(
                <<<'CODE_SAMPLE'
$tags = $GLOBALS['TSFE']->getPageCacheTags();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$tags = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.cache.collector')->getCacheTags();
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $scope = ScopeFetcher::fetch($node);

        $getAttributeCall = $this->createTYPO3RequestGetAttributeMethodCall($scope);

        if ($this->isName($node->name, 'addCacheTags')) {
            $firstArg = $node->args[0]->value;
            if (! $firstArg instanceof Array_) {
                return null;
            }

            $newArgs = [];
            foreach ($firstArg->items as $item) {
                if ($item instanceof ArrayItem && $item->value instanceof String_) {
                    $newArgs[] = new Arg(
                        new New_(
                            new FullyQualified('TYPO3\CMS\Core\Cache\CacheTag'),
                            [new Arg($item->value), new Arg(new Int_(3600))]
                        )
                    );
                }
            }

            // If no valid tags were found, don't make a change
            if ($newArgs === []) {
                return null;
            }

            return new MethodCall($getAttributeCall, 'addCacheTags', $newArgs);
        }

        if ($this->isName($node->name, 'getPageCacheTags')) {
            return new MethodCall($getAttributeCall, 'getCacheTags');
        }

        return null;
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        return ! $this->isGlobals($methodCall)
            && ! $this->isObjectType(
                $methodCall->var,
                new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
            );
    }

    private function isGlobals(MethodCall $methodCall): bool
    {
        return $this->typo3NodeResolver->isAnyMethodCallOnGlobals(
            $methodCall,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }

    private function createTYPO3RequestGetAttributeMethodCall(Scope $scope): MethodCall
    {
        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection
            && $classReflection->is('TYPO3\CMS\Extbase\Mvc\Controller\ActionController')
        ) {
            $requestFetcherVariable = $this->nodeFactory->createPropertyFetch('this', 'request');
        } else {
            $requestFetcherVariable = $this->typo3GlobalsFactory->create('TYPO3_REQUEST');
        }

        return $this->nodeFactory->createMethodCall(
            $requestFetcherVariable,
            'getAttribute',
            [new Arg(new String_('frontend.cache.collector'))]
        );
    }
}
