<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v3;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Database\QueryView;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.3/Deprecation-77557-MethodQueryView-tableWrap.html
 */
final class RefactorQueryViewTableWrapRector extends AbstractRector
{
    /**
<<<<<<< HEAD
     * @return array<class-string<\PhpParser\Node>>
     */

    /**
=======
>>>>>>> 8781ff4... rename AbstractCommunityRectorTestCase to AbstractRectorTestCase
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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, QueryView::class)) {
            return null;
        }
        if (! $this->isName($node->name, 'tableWrap')) {
            return null;
        }

        /** @var Arg[] $args */
        $args = $node->args;
        $firstArgument = array_shift($args);

        if (null === $firstArgument) {
            return null;
        }

        return new Concat(new Concat(new String_('<pre>'), $firstArgument->value), new String_('</pre>'));
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate the method QueryView->tableWrap() to use pre-Tag', [
            new CodeSample(<<<'CODE_SAMPLE'
$queryView = GeneralUtility::makeInstance(QueryView::class);
$output = $queryView->tableWrap('value');
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
$queryView = GeneralUtility::makeInstance(QueryView::class);
$output = '<pre>' . 'value' . '</pre>';
CODE_SAMPLE
            ), ]);
    }
}
