<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v5;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.5/Deprecation-77524-DeprecatedMethodFileResourceOfContentObjectRenderer.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v5\ContentObjectRendererFileResourceRector\ContentObjectRendererFileResourceRectorTest
 */
final class ContentObjectRendererFileResourceRector extends AbstractRector
{
    /**
     * @var string
     */
    private const PATH = 'path';

    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    /**
     * @readonly
     */
    private Typo3GlobalsFactory $typo3GlobalsFactory;

    public function __construct(
        Typo3NodeResolver $typo3NodeResolver,
        Typo3GlobalsFactory $typo3GlobalsFactory
    ) {
        $this->typo3NodeResolver = $typo3NodeResolver;
        $this->typo3GlobalsFactory = $typo3GlobalsFactory;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     * @return Node[]
     */
    public function refactor(Node $node): ?array
    {
        if (! $node->expr instanceof Assign) {
            return null;
        }

        $methodCall = $node->expr->expr;

        if (! $methodCall instanceof MethodCall) {
            return null;
        }

        if ($this->shouldSkip($methodCall)) {
            return null;
        }

        if (! $this->isName($methodCall->name, 'fileResource')) {
            return null;
        }

        return array_filter([
            $this->addInitializeVariableNode($methodCall),
            $this->addTypoScriptFrontendControllerAssignmentNode($methodCall),
            $this->addFileNameNode($methodCall),
            $this->addIfNode($methodCall),
        ]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate fileResource method of class ContentObjectRenderer', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$template = $this->cObj->fileResource('EXT:vendor/Resources/Private/Templates/Template.html');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$path = $GLOBALS['TSFE']->tmpl->getFileName('EXT:vendor/Resources/Private/Templates/Template.html');
if ($path !== null && file_exists($path)) {
    $template = file_get_contents($path);
}
CODE_SAMPLE
            ),
        ]);
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if ($this->isObjectType(
            $methodCall->var,
            new ObjectType('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer')
        )) {
            return false;
        }

        return ! $this->typo3NodeResolver->isMethodCallOnPropertyOfGlobals(
            $methodCall,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER,
            'cObj'
        );
    }

    private function addInitializeVariableNode(MethodCall $methodCall): ?Node
    {
        $parentNode = $methodCall->getAttribute('parent');

        if ($parentNode->var instanceof PropertyFetch) {
            return null;
        }

        return new Expression(new Assign($parentNode->var, new String_('')));
    }

    private function addTypoScriptFrontendControllerAssignmentNode(MethodCall $methodCall): Node
    {
        return new Expression(new Assign(
            new Variable('typoscriptFrontendController'),
            $this->typo3GlobalsFactory->create(Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER)
        ));
    }

    private function addFileNameNode(MethodCall $methodCall): Node
    {
        return new Expression(new Assign(
            new Variable(self::PATH),
            $this->nodeFactory->createMethodCall(
                $this->nodeFactory->createPropertyFetch(new Variable('typoscriptFrontendController'), 'tmpl'),
                'getFileName',
                $methodCall->args
            )
        ));
    }

    private function addIfNode(MethodCall $methodCall): Node
    {
        $parentNode = $methodCall->getAttribute('parent');

        $if = new If_(new BooleanAnd(
            new NotIdentical(new Variable(self::PATH), $this->nodeFactory->createNull()),
            $this->nodeFactory->createFuncCall('file_exists', [new Variable(self::PATH)])
        ));

        $templateAssignment = new Assign($parentNode->var, $this->nodeFactory->createFuncCall(
            'file_get_contents',
            [new Variable(self::PATH)]
        ));
        $if->stmts[] = new Expression($templateAssignment);

        return $if;
    }
}
