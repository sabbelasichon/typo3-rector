<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v5;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.5/Deprecation-77524-DeprecatedMethodFileResourceOfContentObjectRenderer.html
 */
final class ContentObjectRendererFileResourceRector extends AbstractRector
{
    /**
     * @var string
     */
    private const PATH = 'path';

    /**
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

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

        if (! $this->isName($node->name, 'fileResource')) {
            return null;
        }

        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);

        if (! $parentNode instanceof Assign) {
            return null;
        }

        $this->addInitializeVariableNode($node);
        $this->addTypoScriptFrontendControllerAssignmentNode($node);
        $this->addFileNameNode($node);
        $this->addIfNode($node);

        $this->removeNode($parentNode);

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Migrate fileResource method of class ContentObjectRenderer', [
            new CodeSample(<<<'PHP'
$template = $this->cObj->fileResource('EXT:vendor/Resources/Private/Templates/Template.html');
PHP
                , <<<'PHP'
$path = $GLOBALS['TSFE']->tmpl->getFileName('EXT:vendor/Resources/Private/Templates/Template.html');
if ($path !== null && file_exists($path)) {
    $template = file_get_contents($path);
}
PHP
            ),
        ]);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if ($this->isObjectType($node->var, ContentObjectRenderer::class)) {
            return false;
        }
        return ! $this->typo3NodeResolver->isMethodCallOnPropertyOfGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER,
            'cObj'
        );
    }

    private function addInitializeVariableNode(MethodCall $node): void
    {
        $parentNode = $node->getAttribute('parent');
        if (! $parentNode->var instanceof PropertyFetch) {
            $initializeVariable = new Expression(new Assign($parentNode->var, new String_('')));
            $this->addNodeBeforeNode($initializeVariable, $node);
        }
    }

    private function addTypoScriptFrontendControllerAssignmentNode(MethodCall $node): void
    {
        $typoscriptFrontendControllerVariable = new Variable('typoscriptFrontendController');
        $typoscriptFrontendControllerNode = new Assign(
            $typoscriptFrontendControllerVariable,
            new ArrayDimFetch(new Variable('GLOBALS'), new String_(Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER))
        );
        $this->addNodeBeforeNode($typoscriptFrontendControllerNode, $node);
    }

    private function addFileNameNode(MethodCall $node): void
    {
        $fileNameNode = new Assign(
            new Variable(self::PATH),
            $this->createMethodCall(
                $this->createPropertyFetch(new Variable('typoscriptFrontendController'), 'tmpl'),
                'getFileName',
                $node->args
            )
        );
        $this->addNodeBeforeNode($fileNameNode, $node);
    }

    private function addIfNode(MethodCall $node): void
    {
        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);

        $ifNode = new If_(new BooleanAnd(
            new NotIdentical(new Variable(self::PATH), $this->createNull()),
            $this->createFuncCall('file_exists', [new Variable(self::PATH)])
        ));

        $templateAssignment = new Assign($parentNode->var, $this->createFuncCall(
            'file_get_contents',
            [new Variable(self::PATH)]
        ));
        $ifNode->stmts[] = new Expression($templateAssignment);

        $this->addNodeBeforeNode($ifNode, $node);
    }
}
