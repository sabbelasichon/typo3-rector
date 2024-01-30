<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\TYPO311\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.3/Deprecation-94228-DeprecateExtbaseRequestGetRequestUri.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v3\typo3\UseNormalizedParamsToGetRequestUrlRector\UseNormalizedParamsToGetRequestUrlRectorTest
 */
final class UseNormalizedParamsToGetRequestUrlRector extends AbstractRector
{
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

        $node->name = new Identifier('getAttribute');
        $node->args = $this->nodeFactory->createArgs(['normalizedParams']);

        return $this->nodeFactory->createMethodCall($node, 'getRequestUrl');
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use normalized params to get the request url', [new CodeSample(
            <<<'CODE_SAMPLE'
$requestUri = $this->request->getRequestUri();
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$requestUri = $this->request->getAttribute('normalizedParams')->getRequestUrl();
CODE_SAMPLE
        )]);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\\CMS\\Extbase\\Mvc\\Request')
        )) {
            return true;
        }

        return ! $this->isName($node->name, 'getRequestUri');
    }
}
