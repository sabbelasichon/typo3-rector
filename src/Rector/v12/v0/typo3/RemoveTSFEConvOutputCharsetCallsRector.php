<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typo3;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-97065-TYPO3FrontendAlwaysRenderedInUTF-8.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveTSFEConvOutputCharsetCallsRector\RemoveTSFEConvOutputCharsetCallsRectorTest
 */
final class RemoveTSFEConvOutputCharsetCallsRector extends AbstractRector
{
    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
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

        if (! $this->isName($node->name, 'convOutputCharset')) {
            return null;
        }

        return $node->args[0];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Removes usages of TSFE->convOutputCharset(...)', [new CodeSample(
            <<<'CODE_SAMPLE'
$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$foo = $GLOBALS['TSFE']->convOutputCharset($content);
$bar = $tsfe->convOutputCharset('content');
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$foo = $content;
$bar = 'content';
CODE_SAMPLE
        )]);
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if ($this->typo3NodeResolver->isAnyMethodCallOnGlobals(
            $methodCall,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return false;
        }

        return ! $this->isObjectType(
            $methodCall->var,
            new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
        );
    }
}
