<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v5;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/11.5/Deprecation-95219-TypoScriptFrontendController-ATagParams.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v5\HandleCObjRendererATagParamsMethodRector\HandleCObjRendererATagParamsMethodRectorTest
 */
final class HandleCObjRendererATagParamsMethodRector extends AbstractRector
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

        if (1 === count($node->args)) {
            return null;
        }

        // This might be true or 1, so we are not type strict comparing here
        if (! $this->valueResolver->getValue($node->args[1]->value)) {
            return null;
        }

        $node->args = [$node->args[0]];

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Removes deprecated params of the ContentObjectRenderer->getATagParams() method', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$cObjRenderer = GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);
$bar = $cObjRenderer->getATagParams([], false);
CODE_SAMPLE
            ,
                <<<'CODE_SAMPLE'
$cObjRenderer = GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);
$bar = $cObjRenderer->getATagParams([]);
CODE_SAMPLE
            ),
        ]);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer')
        )) {
            return true;
        }

        return ! $this->isName($node->name, 'getATagParams');
    }
}
