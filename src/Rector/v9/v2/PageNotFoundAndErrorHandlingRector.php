<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v2;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Throw_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.2/Deprecation-83883-PageNotFoundAndErrorHandlingInFrontend.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v2\PageNotFoundAndErrorHandlingRector\PageNotFoundAndErrorHandlingRectorTest
 */
final class PageNotFoundAndErrorHandlingRector extends AbstractRector
{
    /**
     * @var array<string, string>
     */
    private const MAP_METHODS = [
        'pageNotFoundAndExit' => 'pageNotFoundAction',
        'pageUnavailableAndExit' => 'unavailableAction',
    ];

    /**
     * @var string[]
     */
    private const METHODS = [
        'pageNotFoundAndExit',
        'pageUnavailableAndExit',
        'checkPageUnavailableHandler',
        'pageUnavailableHandler',
        'pageNotFoundHandler',
        'pageErrorHandler',
    ];

    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Page Not Found And Error handling in Frontend', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class SomeController extends ActionController
{
    public function unavailableAction(): void
    {
        $message = 'No entry found.';
        $GLOBALS['TSFE']->pageUnavailableAndExit($message);
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\ErrorController;

class SomeController extends ActionController
{
    public function unavailableAction(): void
    {
        $message = 'No entry found.';
        $response = GeneralUtility::makeInstance(ErrorController::class)->unavailableAction($GLOBALS['TYPO3_REQUEST'], $message);
        throw new ImmediateResponseException($response);
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, Expression::class];
    }

    /**
     * @param MethodCall|Expression $node
     *
     * @return Node|Node[]|null
     */
    public function refactor(Node $node)
    {
        if ($node instanceof Expression) {
            $methodCall = $node->expr;
        } else {
            $methodCall = $node;
        }

        if (! $methodCall instanceof MethodCall) {
            return null;
        }

        if ($this->shouldSkip($methodCall)) {
            return null;
        }

        if (! $this->isNames($methodCall->name, self::METHODS)) {
            return null;
        }

        if ($this->isName($methodCall->name, 'checkPageUnavailableHandler')) {
            return $this->refactorCheckPageUnavailableHandlerMethod();
        }

        if ($this->isNames($methodCall->name, ['pageUnavailableHandler', 'pageNotFoundHandler', 'pageErrorHandler'])) {
            return $this->refactorPageErrorHandlerIfPossible($methodCall);
        }

        $responseNode = $this->createResponse($methodCall);

        if (! $responseNode instanceof Node) {
            return null;
        }

        return [$responseNode, $this->throwException()];
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

    private function createResponse(MethodCall $methodCall): ?Node
    {
        $methodCallName = $this->getName($methodCall->name);

        if ($methodCallName === null) {
            return null;
        }

        if (! array_key_exists($methodCallName, self::MAP_METHODS)) {
            return null;
        }

        $arguments = [new ArrayDimFetch(new Variable(Typo3NodeResolver::GLOBALS), new String_('TYPO3_REQUEST'))];

        // Message
        $arguments[] = isset($methodCall->args[0]) ? $methodCall->args[0]->value : new String_('');

        return new Expression(
            new Assign(
                new Variable('response'),
                $this->nodeFactory->createMethodCall(
                    $this->nodeFactory->createStaticCall('TYPO3\CMS\Core\Utility\GeneralUtility', 'makeInstance', [
                        $this->nodeFactory->createClassConstReference('TYPO3\CMS\Frontend\Controller\ErrorController'),
                    ]),
                    self::MAP_METHODS[$methodCallName],
                    $arguments
                )
            )
        );
    }

    private function throwException(): Node
    {
        return new Throw_(
            new New_(new Name('TYPO3\CMS\Core\Http\ImmediateResponseException'), $this->nodeFactory->createArgs(
                [new Variable('response')]
            ))
        );
    }

    private function refactorCheckPageUnavailableHandlerMethod(): Node
    {
        $devIpMask = new ArrayDimFetch(
            new ArrayDimFetch(
                new ArrayDimFetch(new Variable(Typo3NodeResolver::GLOBALS), new String_('TYPO3_CONF_VARS')),
                new String_('SYS')
            ),
            new String_('devIPmask')
        );

        $pageUnavailableHandling = new ArrayDimFetch(
            new ArrayDimFetch(
                new ArrayDimFetch(new Variable(Typo3NodeResolver::GLOBALS), new String_('TYPO3_CONF_VARS')),
                new String_('FE')
            ),
            new String_('pageUnavailable_handling')
        );

        return new BooleanAnd(
            $pageUnavailableHandling,
            new BooleanNot(
                $this->nodeFactory->createStaticCall('TYPO3\CMS\Core\Utility\GeneralUtility', 'cmpIP', [
                    $this->nodeFactory->createStaticCall(
                        'TYPO3\CMS\Core\Utility\GeneralUtility',
                        'getIndpEnv',
                        [new String_('REMOTE_ADDR')]
                    ),
                    $devIpMask,
                ])
            )
        );
    }

    private function refactorPageErrorHandlerIfPossible(MethodCall $methodCall): ?Node
    {
        if (! isset($methodCall->args[0])) {
            return null;
        }

        $code = $this->valueResolver->getValue($methodCall->args[0]->value);

        if ($code === null) {
            return null;
        }

        $message = null;
        if ((string) $code === '1' || is_bool($code) || strtolower((string) $code) === 'true') {
            $message = new String_('The page did not exist or was inaccessible.');
            if (isset($methodCall->args[2])) {
                $reason = $methodCall->args[2]->value;
                $message = $this->nodeFactory->createConcat([
                    $message,
                    new Ternary($reason, $this->nodeFactory->createConcat(
                        [new String_(' Reason: '), $reason]
                    ), new String_('')),
                ]);
            }
        }

        if ($code === '') {
            $message = new String_('Page cannot be found.');
            if (isset($methodCall->args[2])) {
                $reason = $methodCall->args[2]->value;
                $message = new Ternary($reason, $this->nodeFactory->createConcat(
                    [new String_('Reason: '), $reason]
                ), $message);
            }
        }

        if ($message !== null) {
            return new Echo_([
                $this->nodeFactory->createMethodCall(
                    $this->nodeFactory->createStaticCall(
                        'TYPO3\CMS\Core\Utility\GeneralUtility',
                        'makeInstance',
                        [$this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Controller\ErrorPageController')]
                    ),
                    'errorAction',
                    ['Page Not Found', $message]
                ),
            ]);
        }

        return null;
    }
}
