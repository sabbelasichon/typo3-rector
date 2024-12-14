<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96107-DeprecatedFunctionalityRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\ExtbaseActionsWithRedirectMustReturnResponseInterfaceRector\ExtbaseActionsWithRedirectMustReturnResponseInterfaceRectorTest
 */
final class ExtbaseActionsWithRedirectMustReturnResponseInterfaceRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Extbase controller actions with redirects must return ResponseInterface', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function someAction()
    {
        $this->redirect('foo', 'bar');
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function someAction(): ResponseInterface
    {
        return $this->redirect('foo', 'bar');
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Extbase\Mvc\Controller\ActionController')
        )) {
            return null;
        }

        $hasChanged = false;

        $this->traverseNodesWithCallable($node, function (Node $node) use (&$hasChanged) {
            if (! $node instanceof Expression) {
                return null;
            }

            $methodCall = $node->expr;
            if (! $methodCall instanceof MethodCall) {
                return null;
            }

            if (! $this->isNames($methodCall->name, ['redirect', 'redirectToUri'])) {
                return null;
            }

            $returnMethodCall = new Return_($methodCall);
            $hasChanged = true;
            return $returnMethodCall;
        });

        if (! $hasChanged) {
            return null;
        }

        $this->changeActionMethodReturnTypeIfPossible($node);

        return $node;
    }

    private function changeActionMethodReturnTypeIfPossible(ClassMethod $actionMethodNode): void
    {
        if ($actionMethodNode->returnType instanceof Identifier
            && $actionMethodNode->returnType->name === 'void'
        ) {
            $actionMethodNode->returnType = null;
        }

        $comments = $actionMethodNode->getComments();
        $comments = array_map(
            static fn (Comment $comment) => new Comment(str_replace(
                '@return void',
                '@return \Psr\Http\Message\ResponseInterface',
                $comment->getText()
            )),
            $comments
        );
        $actionMethodNode->setAttribute(AttributeKey::COMMENTS, $comments);

        // Add returnType only if it is the only statement, otherwise it is not reliable
        if (is_countable($actionMethodNode->stmts) && count($actionMethodNode->stmts) === 1) {
            $actionMethodNode->returnType = new FullyQualified('Psr\Http\Message\ResponseInterface');
        }
    }
}
