<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Lowlevel\Utility\ArrayBrowser;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-80440-EXTlowlevelArrayBrowser-wrapValue.html
 */
final class RefactorArrayBrowserWrapValueRector extends AbstractRector
{
    /**
     * @return array<class-string<\PhpParser\Node>>
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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, ArrayBrowser::class)) {
            return null;
        }
        if (! $this->isName($node->name, 'wrapValue')) {
            return null;
        }
        /** @var Arg[] $args */
        $args = $node->args;
        $firstArgument = array_shift($args);
        return $this->nodeFactory->createFuncCall('htmlspecialchars', [$firstArgument]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate the method ArrayBrowser->wrapValue() to use htmlspecialchars()', [
            new CodeSample(<<<'CODE_SAMPLE'
$arrayBrowser = GeneralUtility::makeInstance(ArrayBrowser::class);
$arrayBrowser->wrapValue('value');
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
$arrayBrowser = GeneralUtility::makeInstance(ArrayBrowser::class);
htmlspecialchars('value');
CODE_SAMPLE
            ), ]);
    }
}
