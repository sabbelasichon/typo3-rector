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
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Controller\ErrorPageController;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\ErrorController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.2/Deprecation-83883-PageNotFoundAndErrorHandlingInFrontend.html
 */
final class PageNotFoundAndErrorHandlingRector extends AbstractRector
{
    /**
     * @var array
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
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

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
            new CodeSample(<<<'CODE_SAMPLE'
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
                , <<<'CODE_SAMPLE'
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

        if (! $this->isNames($node->name, self::METHODS)) {
            return null;
        }

        if ($this->isName($node->name, 'checkPageUnavailableHandler')) {
            return $this->refactorCheckPageUnavailableHandlerMethod();
        }

        $currentStmts = $node->getAttribute(AttributeKey::CURRENT_STATEMENT);
        $positionNode = $currentStmts ?? $node;

        if ($this->isNames($node->name, ['pageUnavailableHandler', 'pageNotFoundHandler', 'pageErrorHandler'])) {
            $newNode = $this->refactorPageErrorHandlerIfPossible($node);
            if (null !== $newNode) {
                $this->addNodeBeforeNode($newNode, $positionNode);
                $this->removeNodeOrParentNode($node);
            }

            return null;
        }

        $responseNode = $this->createResponse($node);

        if (null === $responseNode) {
            return null;
        }

        $this->addNodeBeforeNode($responseNode, $positionNode);
        $this->addNodeBeforeNode($this->throwException(), $positionNode);
        $this->removeNodeOrParentNode($node);

        return $node;
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if ($this->typo3NodeResolver->isAnyMethodCallOnGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return false;
        }

        return ! $this->isObjectType($node->var, new ObjectType(TypoScriptFrontendController::class));
    }

    private function createResponse(MethodCall $node): ?Node
    {
        $methodCall = $this->getName($node->name);

        if (null === $methodCall) {
            return null;
        }

        if (! array_key_exists($methodCall, self::MAP_METHODS)) {
            return null;
        }

        $arguments = [new ArrayDimFetch(new Variable(Typo3NodeResolver::GLOBALS), new String_('TYPO3_REQUEST'))];

        // Message
        if (isset($node->args[0])) {
            $arguments[] = $node->args[0]->value;
        }

        return new Expression(
            new Assign(new Variable('response'),
                $this->nodeFactory->createMethodCall(
                    $this->nodeFactory->createStaticCall(GeneralUtility::class, 'makeInstance', [
                        $this->nodeFactory->createClassConstReference(ErrorController::class),
                    ]),
                    self::MAP_METHODS[$methodCall],
                    $arguments
                )
            )
        );
    }

    private function throwException(): Node
    {
        return new Throw_(
            new New_(new Name(ImmediateResponseException::class), $this->nodeFactory->createArgs(
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
            ), new String_('devIPmask')
        );

        $pageUnavailableHandling = new ArrayDimFetch(
            new ArrayDimFetch(
                new ArrayDimFetch(new Variable(Typo3NodeResolver::GLOBALS), new String_('TYPO3_CONF_VARS')),
                new String_('FE')
            ), new String_('pageUnavailable_handling')
        );

        return new BooleanAnd(
            $pageUnavailableHandling,
            new BooleanNot(
                $this->nodeFactory->createStaticCall(GeneralUtility::class, 'cmpIP', [
                    $this->nodeFactory->createStaticCall(
                        GeneralUtility::class,
                        'getIndpEnv',
                        [new String_('REMOTE_ADDR')]
                    ),
                    $devIpMask,
                ])
            )
        );
    }

    private function refactorPageErrorHandlerIfPossible(MethodCall $node): ?Node
    {
        if (! isset($node->args[0])) {
            return null;
        }

        $code = $this->valueResolver->getValue($node->args[0]->value);

        if (null === $code) {
            return null;
        }

        $message = null;
        if ('1' === (string) $code || is_bool($code) || 'true' === strtolower($code)) {
            $message = new String_('The page did not exist or was inaccessible.');
            if (isset($node->args[2])) {
                $reason = $node->args[2]->value;
                $message = $this->nodeFactory->createConcat([
                    $message,
                    new Ternary($reason, $this->nodeFactory->createConcat(
                        [new String_(' Reason: '), $reason]
                    ), new String_('')),
                ]);
            }
        }

        if ('' === $code) {
            $message = new String_('Page cannot be found.');
            if (isset($node->args[2])) {
                $reason = $node->args[2]->value;
                $message = new Ternary($reason, $this->nodeFactory->createConcat(
                    [new String_('Reason: '), $reason]
                ), $message);
            }
        }

        if (null !== $message) {
            return new Echo_([
                $this->nodeFactory->createMethodCall(
                    $this->nodeFactory->createStaticCall(
                        GeneralUtility::class,
                        'makeInstance',
                        [$this->nodeFactory->createClassConstReference(ErrorPageController::class)]
                    ),
                    'errorAction',
                    ['Page Not Found', $message]
                ),
            ]);
        }

        return null;
    }

    private function removeNodeOrParentNode(Node $node): void
    {
        try {
            $this->removeNode($node);
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            $this->removeNode($node->getAttribute(AttributeKey::PARENT_NODE));
        }
    }
}
