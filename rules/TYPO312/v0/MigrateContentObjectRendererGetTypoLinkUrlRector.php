<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-96641-LinkRelatedFunctionalityInContentObjectRenderer.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateContentObjectRendererGetTypoLinkUrlRector\MigrateContentObjectRendererGetTypoLinkUrlRectorTest
 */
final class MigrateContentObjectRendererGetTypoLinkUrlRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate ContentObjectRenderer->getTypoLink_URL to ContentObjectRenderer->createUrl',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$contentObjectRenderer->typoLink_URL(12);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$contentObjectRenderer->createUrl(['parameter' => 12]);
CODE_SAMPLE
                ),
            ]
        );
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

        // params
        $arguments['parameter'] = $node->args[0];

        // urlParameters
        if (isset($node->args[1])) {
            if ($node->args[1]->value instanceof String_) {
                $urlParameters = $this->valueResolver->getValue($node->args[1]->value);
                if (is_string($urlParameters)) {
                    $arguments['additionalParams'] = $urlParameters;
                } elseif (is_array($urlParameters)) {
                    $staticCall = $this->nodeFactory->createStaticCall(
                        'TYPO3\\CMS\\Core\\Utility\\HttpUtility',
                        'buildQueryString',
                        [$this->nodeFactory->createArg($urlParameters), $this->nodeFactory->createArg('&')]
                    );

                    $arguments['additionalParams'] = $staticCall;
                }
            } elseif ($node->args[1]->value instanceof Variable) {
                $urlParameters = $this->valueResolver->getValue($node->args[1]->value);
                if (is_string($urlParameters)) {
                    $arguments['additionalParams'] = $node->args[1];
                } elseif (is_array($urlParameters)) {
                    $staticCall = $this->nodeFactory->createStaticCall(
                        'TYPO3\\CMS\\Core\\Utility\\HttpUtility',
                        'buildQueryString',
                        [$node->args[1], $this->nodeFactory->createArg('&')]
                    );

                    $arguments['additionalParams'] = $staticCall;
                }
            } elseif ($node->args[1]->value instanceof Array_) {
                $staticCall = $this->nodeFactory->createStaticCall(
                    'TYPO3\\CMS\\Core\\Utility\\HttpUtility',
                    'buildQueryString',
                    [$node->args[1], $this->nodeFactory->createArg('&')]
                );

                $arguments['additionalParams'] = $staticCall;
            } elseif ($node->args[1]->value instanceof Concat) {
                $arguments['additionalParams'] = $node->args[1];
            }
        }

        // Target
        if (isset($node->args[2])) {
            $target = $this->valueResolver->getValue($node->args[2]->value);
            $arguments['target'] = $target;
            $arguments['extTarget'] = $target;
            $arguments['fileTarget'] = $target;
        }

        $node->name = new Identifier('createUrl');
        $node->args = $this->nodeFactory->createArgs([$this->nodeFactory->createArray($arguments)]);

        return $node;
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer')
        )) {
            return true;
        }

        return ! $this->nodeNameResolver->isName($node->name, 'getTypoLink_URL');
    }
}
