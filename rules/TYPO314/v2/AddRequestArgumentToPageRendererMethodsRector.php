<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v2;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;
use Ssch\TYPO3Rector\NodeFactory\Typo3RequestNodeFactory;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.2/Deprecation-109286-ExplicitRequestHandlingInPageRenderer.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v2\AddRequestArgumentToPageRendererMethodsRector\AddRequestArgumentToPageRendererMethodsRectorTest
 */
final class AddRequestArgumentToPageRendererMethodsRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var array<string, int>
     */
    private const APPENDED_REQUEST_POSITION = [
        'setLanguage' => 1,
        'setDocType' => 1,
        'render' => 0,
    ];

    /**
     * @readonly
     */
    private Typo3RequestNodeFactory $typo3RequestNodeFactory;

    public function __construct(Typo3RequestNodeFactory $typo3RequestNodeFactory)
    {
        $this->typo3RequestNodeFactory = $typo3RequestNodeFactory;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Hand over the request to PageRenderer methods explicitly', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
$pageRenderer->setLanguage($locale);
$pageRenderer->setDocType($docType);
$content = $pageRenderer->render();
$response = $pageRenderer->renderResponse(404);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
$pageRenderer->setLanguage($locale, $GLOBALS['TYPO3_REQUEST']);
$pageRenderer->setDocType($docType, $GLOBALS['TYPO3_REQUEST']);
$content = $pageRenderer->render($GLOBALS['TYPO3_REQUEST']);
$response = $pageRenderer->renderResponse($GLOBALS['TYPO3_REQUEST'], 404);
CODE_SAMPLE
        )]);
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
        if ($node->isFirstClassCallable()) {
            return null;
        }

        $methodName = $this->getName($node->name);
        if ($methodName === null) {
            return null;
        }

        if (! isset(self::APPENDED_REQUEST_POSITION[$methodName]) && $methodName !== 'renderResponse') {
            return null;
        }

        if (! $this->isObjectType($node->var, new ObjectType('TYPO3\CMS\Core\Page\PageRenderer'))) {
            return null;
        }

        $args = $node->getArgs();
        foreach ($args as $arg) {
            if ($arg->unpack || $arg->name !== null) {
                return null;
            }
        }

        if ($methodName === 'renderResponse') {
            $requestExpr = $this->createRequestExpr($node);
            if (! $this->canPrependRequestToRenderResponse($args, $requestExpr)) {
                return null;
            }

            array_unshift($node->args, new Arg($requestExpr));
            return $node;
        }

        if (count($args) !== self::APPENDED_REQUEST_POSITION[$methodName]) {
            return null;
        }

        $node->args[] = new Arg($this->createRequestExpr($node));
        return $node;
    }

    /**
     * @param Arg[] $args
     */
    private function canPrependRequestToRenderResponse(array $args, Expr $requestExpr): bool
    {
        if (count($args) > 2) {
            return false;
        }

        if ($args === []) {
            return true;
        }

        if ($this->nodeComparator->areNodesEqual($args[0]->value, $requestExpr)) {
            return false;
        }

        return ! $this->isObjectType($args[0]->value, new ObjectType('Psr\Http\Message\ServerRequestInterface'));
    }

    private function createRequestExpr(MethodCall $methodCall): Expr
    {
        return $this->typo3RequestNodeFactory->getServerRequestInScope(ScopeFetcher::fetch($methodCall));
    }
}
