<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v0;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Deprecation-92815-ActionControllerForward.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v0\ForwardResponseInsteadOfForwardMethodRector\ForwardResponseInsteadOfForwardMethodRectorTest
 */
final class ForwardResponseInsteadOfForwardMethodRector extends AbstractRector
{
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Return TYPO3\CMS\Extbase\Http\ForwardResponse instead of ' . ActionController::class . '::forward()',
            [new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class FooController extends ActionController
{
   public function listAction()
   {
        $this->forward('show');
   }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Http\ForwardResponse;

class FooController extends ActionController
{
   public function listAction(): ResponseInterface
   {
        return new ForwardResponse('show');
   }
}
CODE_SAMPLE
            )]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
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

            if (! $this->isName($methodCall->name, 'forward')) {
                return null;
            }

            $forwardResponse = $this->createForwardResponseNode($methodCall);

            if ($forwardResponse === null) {
                return null;
            }

            $forwardResponseReturn = new Return_($forwardResponse);
            $hasChanged = true;
            return $forwardResponseReturn;
        });

        if (! $hasChanged) {
            return null;
        }

        $this->changeActionMethodReturnTypeIfPossible($node);

        return $node;
    }

    /**
     * @return MethodCall|New_|null
     */
    public function createForwardResponseNode(MethodCall $forwardMethodCall)
    {
        $forwardMethodCallArguments = $forwardMethodCall->args;

        $action = $this->valueResolver->getValue($forwardMethodCallArguments[0]->value);

        if ($action === null) {
            return null;
        }

        $args = $this->nodeFactory->createArgs([$action]);

        $forwardResponse = new New_(new FullyQualified('TYPO3\CMS\Extbase\Http\ForwardResponse'), $args);

        if (isset($forwardMethodCallArguments[1]) && ! $this->valueResolver->isNull(
            $forwardMethodCallArguments[1]->value
        )) {
            $forwardResponse = $this->nodeFactory->createMethodCall(
                $forwardResponse,
                'withControllerName',
                [$forwardMethodCallArguments[1]->value]
            );
        }

        if (isset($forwardMethodCallArguments[2]) && ! $this->valueResolver->isNull(
            $forwardMethodCallArguments[2]->value
        )) {
            $forwardResponse = $this->nodeFactory->createMethodCall(
                $forwardResponse,
                'withExtensionName',
                [$forwardMethodCallArguments[2]->value]
            );
        }

        if (isset($forwardMethodCallArguments[3])) {
            $forwardResponse = $this->nodeFactory->createMethodCall(
                $forwardResponse,
                'withArguments',
                [$forwardMethodCallArguments[3]->value]
            );
        }

        return $forwardResponse;
    }

    private function changeActionMethodReturnTypeIfPossible(ClassMethod $actionMethodNode): void
    {
        if ($actionMethodNode->returnType instanceof Identifier && $actionMethodNode->returnType->name !== null && $actionMethodNode->returnType->name === 'void') {
            $actionMethodNode->returnType = null;
        }

        $comments = $actionMethodNode->getComments();
        $comments = array_map(
            static fn (Comment $comment) => new Comment(str_replace(' @return void', '', $comment->getText())),
            $comments
        );
        $actionMethodNode->setAttribute(AttributeKey::COMMENTS, $comments);

        // Add returnType only if it is the only statement, otherwise it is not reliable
        if (is_countable($actionMethodNode->stmts) && count((array) $actionMethodNode->stmts) === 1) {
            $actionMethodNode->returnType = new FullyQualified('Psr\Http\Message\ResponseInterface');
        }
    }
}
