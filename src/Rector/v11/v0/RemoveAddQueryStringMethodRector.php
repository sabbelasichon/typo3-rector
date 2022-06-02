<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v0;

use PhpParser\Builder\Method;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\Defluent\NodeAnalyzer\FluentChainMethodCallNodeAnalyzer;
use Rector\Defluent\NodeAnalyzer\SameClassMethodCallAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/11.0/Breaking-93041-RemoveTypoScriptOptionAddQueryStringmethod.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v0\RemoveAddQueryStringMethodRector\RemoveAddQueryStringMethodRectorTest
 */
final class RemoveAddQueryStringMethodRector extends AbstractRector
{
    public function __construct(
        private readonly FluentChainMethodCallNodeAnalyzer $fluentChainMethodCallNodeAnalyzer,
        private readonly SameClassMethodCallAnalyzer $sameClassMethodCallAnalyzer
    ) {
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

        if ($this->isMethodCallOnContentObjectRenderer($node)) {
            $this->refactorGetQueryArgumentsMethodCall($node);
            return null;
        }

        return $this->refactorSetAddQueryStringMethodCall($node);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove TypoScript option addQueryString.method', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$this->uriBuilder->setUseCacheHash(true)
    ->setCreateAbsoluteUri(true)
    ->setAddQueryString(true)
    ->setAddQueryStringMethod('GET')
    ->build();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$this->uriBuilder->setUseCacheHash(true)
    ->setCreateAbsoluteUri(true)
    ->setAddQueryString(true)
    ->build();
CODE_SAMPLE
            ),
        ]);
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if ($this->isMethodCallOnUriBuilder($methodCall)) {
            return false;
        }

        return ! $this->isMethodCallOnContentObjectRenderer($methodCall);
    }

    private function isMethodCallOnUriBuilder(MethodCall $methodCall): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
        )) {
            return false;
        }

        return $this->isName($methodCall->name, 'setAddQueryStringMethod');
    }

    private function isMethodCallOnContentObjectRenderer(MethodCall $methodCall): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer')
        )) {
            return false;
        }

        return $this->isName($methodCall->name, 'getQueryArguments');
    }

    private function refactorSetAddQueryStringMethodCall(MethodCall $methodCall): ?Node
    {
        try {
            // If it is the only method call, we can safely delete the node here.
            $this->removeNode($methodCall);

            return $methodCall;
        } catch (ShouldNotHappenException) {
            $chainMethodCalls = $this->fluentChainMethodCallNodeAnalyzer->collectAllMethodCallsInChain($methodCall);

            if (! $this->sameClassMethodCallAnalyzer->haveSingleClass($chainMethodCalls)) {
                return null;
            }

            foreach ($chainMethodCalls as $chainMethodCall) {
                if ($this->isName($methodCall->name, 'setAddQueryStringMethod')) {
                    continue;
                }

                $methodCall->var = new MethodCall(
                    $chainMethodCall->var,
                    $chainMethodCall->name,
                    $chainMethodCall->args
                );
            }

            return $methodCall->var;
        }
    }

    private function refactorGetQueryArgumentsMethodCall(MethodCall $methodCall): void
    {
        unset($methodCall->args[1], $methodCall->args[2]);
    }
}
