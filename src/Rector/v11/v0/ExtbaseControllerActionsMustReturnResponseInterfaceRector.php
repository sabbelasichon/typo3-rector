<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v0;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\Exit_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use Psr\Http\Message\ResponseInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/11.0/Deprecation-92784-ExtbaseControllerActionsMustReturnResponseInterface.html
 */
final class ExtbaseControllerActionsMustReturnResponseInterfaceRector extends AbstractRector
{
    /**
     * @var string
     */
    private const THIS = 'this';

    /**
     * @return string[]
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

        foreach ($this->extractReturnCalls($node) as $returnCall) {
            if (! $returnCall instanceof Return_) {
                continue;
            }

            $returnCallExpression = $returnCall->expr;

            if ($returnCallExpression instanceof FuncCall && $this->isName(
                $returnCallExpression->name,
                'json_encode'
            )) {
                $returnCall->expr = $this->nodeFactory->createMethodCall(
                    $this->nodeFactory->createPropertyFetch(self::THIS, 'responseFactory'),
                    'createJsonResponse',
                    [$returnCall->expr]
                );
            } else {
                $returnCall->expr = $this->nodeFactory->createMethodCall(
                    self::THIS,
                    'htmlResponse',
                    [$returnCall->expr]
                );
            }
        }

        $node->returnType = new FullyQualified(ResponseInterface::class);

        $statements = $node->stmts;
        $lastStatement = null;

        if (is_array($statements)) {
            $lastStatement = array_pop($statements);
        }

        if (! $lastStatement instanceof Return_) {
            $returnResponse = $this->nodeFactory->createMethodCall(self::THIS, 'htmlResponse');

            $node->stmts[] = new Return_($returnResponse);
        }

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Extbase controller actions must return ResponseInterface', [new CodeSample(<<<'PHP'
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
class MyController extends ActionController
{
    public function someAction()
    {
        $this->view->assign('foo', 'bar');
    }
}
PHP
            , <<<'PHP'
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
PHP
        )]);
    }

    private function shouldSkip(ClassMethod $node): bool
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, ActionController::class)) {
            return true;
        }

        if (! $node->isPublic()) {
            return true;
        }

        $methodName = $this->getName($node->name);

        if (null === $methodName) {
            return true;
        }

        if (! Strings::endsWith($methodName, 'Action')) {
            return true;
        }

        if (Strings::startsWith($methodName, 'initialize')) {
            return true;
        }

        if ($this->hasExitCall($node)) {
            return true;
        }

        if ($this->hasRedirectCall($node)) {
            return true;
        }
        return $this->alreadyResponseReturnType($node);
    }

    /**
     * @return Return_[]|Node[]
     */
    private function extractReturnCalls(ClassMethod $node): array
    {
        return $this->betterNodeFinder->find((array) $node->stmts, function (Node $node): bool {
            return $node instanceof Return_;
        });
    }

    private function hasRedirectCall(ClassMethod $node): bool
    {
        return (bool) $this->betterNodeFinder->find((array) $node->stmts, function (Node $node): bool {
            if (! $node instanceof MethodCall) {
                return false;
            }

            if (! $this->isMethodStaticCallOrClassMethodObjectType($node, ActionController::class)) {
                return false;
            }

            return $this->isNames($node->name, ['redirect', 'redirectToUri']);
        });
    }

    private function hasExitCall(ClassMethod $node): bool
    {
        return (bool) $this->betterNodeFinder->find((array) $node->stmts, function (Node $node): bool {
            return $node instanceof Exit_;
        });
    }

    private function alreadyResponseReturnType(ClassMethod $node): bool
    {
        foreach ($this->extractReturnCalls($node) as $returnCall) {
            if (! $returnCall instanceof Return_) {
                continue;
            }

            if ($this->isReturnOfObjectType($returnCall, ResponseInterface::class)) {
                return true;
            }
        }
        return false;
    }
}
