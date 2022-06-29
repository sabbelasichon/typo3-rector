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
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/11.0/Deprecation-92815-ActionControllerForward.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v0\ForwardResponseInsteadOfForwardMethodRector\ForwardResponseInsteadOfForwardMethodRectorTest
 */
final class ForwardResponseInsteadOfForwardMethodRector extends AbstractRector
{
    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Return TYPO3\CMS\Extbase\Http\ForwardResponse instead of TYPO3\CMS\Extbase\Mvc\Controller\ActionController::forward()',
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
        $forwardMethodCalls = $this->extractForwardMethodCalls($node);
        if ([] === $forwardMethodCalls) {
            return null;
        }

        foreach ($forwardMethodCalls as $forwardMethodCall) {
            $action = $this->valueResolver->getValue($forwardMethodCall->args[0]->value);

            if (null === $action) {
                return null;
            }

            $args = $this->nodeFactory->createArgs([$action]);
            $forwardResponse = new New_(new FullyQualified('TYPO3\CMS\Extbase\Http\ForwardResponse'), $args);

            if (isset($forwardMethodCall->args[1]) && ! $this->valueResolver->isNull(
                $forwardMethodCall->args[1]->value
            )) {
                $forwardResponse = $this->nodeFactory->createMethodCall(
                    $forwardResponse,
                    'withControllerName',
                    [$forwardMethodCall->args[1]->value]
                );
            }

            if (isset($forwardMethodCall->args[2]) && ! $this->valueResolver->isNull(
                $forwardMethodCall->args[2]->value
            )) {
                $forwardResponse = $this->nodeFactory->createMethodCall(
                    $forwardResponse,
                    'withExtensionName',
                    [$forwardMethodCall->args[2]->value]
                );
            }

            if (isset($forwardMethodCall->args[3])) {
                $forwardResponse = $this->nodeFactory->createMethodCall(
                    $forwardResponse,
                    'withArguments',
                    [$forwardMethodCall->args[3]->value]
                );
            }

            $forwardResponseReturn = new Return_($forwardResponse);

            $this->nodesToAddCollector->addNodeBeforeNode($forwardResponseReturn, $forwardMethodCall);
            $this->removeNode($forwardMethodCall);
        }

        if (null !== $node->returnType && $node->returnType instanceof Identifier && null !== $node->returnType->name && 'void' === $node->returnType->name) {
            $node->returnType = null;
        }

        $comments = $node->getComments();
        $comments = array_map(
            static fn (Comment $comment) => new Comment(str_replace(' @return void', '', $comment->getText())),
            $comments
        );
        $node->setAttribute('comments', $comments);

        // Add returnType only if it is the only statement, otherwise it is not reliable
        if (is_countable($node->stmts) && 1 === count((array) $node->stmts)) {
            $node->returnType = new FullyQualified('Psr\Http\Message\ResponseInterface');
        }

        return $node;
    }

    /**
     * @return MethodCall[]
     */
    private function extractForwardMethodCalls(ClassMethod $classMethod): array
    {
        return $this->betterNodeFinder->find((array) $classMethod->stmts, function (Node $node): bool {
            if (! $node instanceof MethodCall) {
                return false;
            }

            if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
                $node,
                new ObjectType('TYPO3\CMS\Extbase\Mvc\Controller\ActionController')
            )) {
                return false;
            }

            return $this->isName($node->name, 'forward');
        });
    }
}
