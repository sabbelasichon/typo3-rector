<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Exit_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Throw_;
use PhpParser\NodeTraverser;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeAnalyzer\ExtbaseControllerRedirectAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Deprecation-92784-ExtbaseControllerActionsMustReturnResponseInterface.html
 *
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v0\ExtbaseControllerActionsMustReturnResponseInterfaceRector\ExtbaseControllerActionsMustReturnResponseInterfaceRectorTest
 */
final class ExtbaseControllerActionsMustReturnResponseInterfaceRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<int, string>
     */
    private array $redirectMethods = ['redirect', 'redirectToUri'];

    private ExtbaseControllerRedirectAnalyzer $extbaseControllerRedirectAnalyzer;

    public function __construct(ExtbaseControllerRedirectAnalyzer $extbaseControllerRedirectAnalyzer)
    {
        $this->extbaseControllerRedirectAnalyzer = $extbaseControllerRedirectAnalyzer;
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $this->traverseNodesWithCallable($node, function (Node $node) {
            if ($node instanceof Class_ || $node instanceof Function_ || $node instanceof Closure) {
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if (! $node instanceof Return_) {
                return null;
            }

            $responseObjectType = new ObjectType('Psr\\Http\\Message\\ResponseInterface');

            if ($node->expr !== null && $this->isObjectType($node->expr, $responseObjectType)) {
                return null;
            }

            $returnCallExpression = $node->expr;

            if ($returnCallExpression instanceof FuncCall && $this->isName(
                $returnCallExpression->name,
                'json_encode'
            )) {
                return new Return_($this->nodeFactory->createMethodCall(
                    'this',
                    'jsonResponse',
                    [$returnCallExpression]
                ));
            }
            // avoid duplication
            if ($node->expr instanceof MethodCall && $this->isName($node->expr->name, 'htmlResponse')) {
                $args = [];
            } else {
                $args = [$node->expr];
            }

            return new Return_($this->createHtmlResponseMethodCall($args));
        });

        $node->returnType = new FullyQualified('Psr\Http\Message\ResponseInterface');

        $statements = $node->stmts;
        $lastStatement = null;

        if (is_array($statements)) {
            $lastStatement = array_pop($statements);
        }

        if (! $lastStatement instanceof Return_) {
            $node->stmts[] = new Return_($this->createHtmlResponseMethodCall([]));
        }

        return $node;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Extbase controller actions must return ResponseInterface', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function someAction()
    {
        $this->view->assign('foo', 'bar');
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
        $this->view->assign('foo', 'bar');
        return $this->htmlResponse();
    }
}
CODE_SAMPLE
                ,
                [
                    'redirect_methods' => ['myRedirectMethod'],
                ]
            ),
        ]);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $redirectMethods = $configuration['redirect_methods'] ?? $configuration;
        Assert::isArray($redirectMethods);
        Assert::allString($redirectMethods);

        $this->redirectMethods = array_unique(array_merge($redirectMethods, $this->redirectMethods));
    }

    private function shouldSkip(ClassMethod $classMethod): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $classMethod,
            new ObjectType('TYPO3\CMS\Extbase\Mvc\Controller\ActionController')
        )) {
            return true;
        }

        if (! $classMethod->isPublic()) {
            return true;
        }

        if ($classMethod->isAbstract()) {
            return true;
        }

        $methodName = $this->getName($classMethod->name);

        if ($methodName === null) {
            return true;
        }

        if (! \str_ends_with($methodName, 'Action')) {
            return true;
        }

        if (\str_starts_with($methodName, 'initialize')) {
            return true;
        }

        if ($this->extbaseControllerRedirectAnalyzer->hasRedirectCall($classMethod, $this->redirectMethods)) {
            return true;
        }

        if ($classMethod->stmts === null) {
            return false;
        }

        $statements = $classMethod->stmts;
        $lastStatement = array_pop($statements);

        if ($lastStatement === null) {
            return false;
        }

        if ($this->lastStatementIsExitCall($lastStatement)) {
            return true;
        }

        if ($this->lastStatementIsForwardCall($lastStatement)) {
            return true;
        }

        return $this->hasExceptionCall($lastStatement);
    }

    private function lastStatementIsExitCall(Node $lastStatement): bool
    {
        return $lastStatement instanceof Expression && $lastStatement->expr instanceof Exit_;
    }

    private function hasExceptionCall(Node $lastStatement): bool
    {
        if (! $lastStatement instanceof Throw_) {
            return false;
        }

        $propagateResponseException = new ObjectType('TYPO3\CMS\Core\Http\PropagateResponseException');

        return $this->getType($lastStatement->expr)
            ->isSuperTypeOf($propagateResponseException)
            ->yes();
    }

    private function lastStatementIsForwardCall(Node $lastStatement): bool
    {
        if (! $lastStatement instanceof Expression) {
            return false;
        }

        if (! ($lastStatement->expr instanceof MethodCall)) {
            return false;
        }

        return $this->isName($lastStatement->expr->name, 'forward');
    }

    /**
     * @param mixed[] $args
     */
    private function createHtmlResponseMethodCall(array $args): MethodCall
    {
        return $this->nodeFactory->createMethodCall('this', 'htmlResponse', $args);
    }
}
