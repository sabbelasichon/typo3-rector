<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\ValueObject\Type\FullyQualifiedIdentifierTypeNode;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.3/Deprecation-94223-ExtbaseRequest-getBaseUri.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v3\SubstituteExtbaseRequestGetBaseUriRector\SubstituteExtbaseRequestGetBaseUriRectorTest
 */
final class SubstituteExtbaseRequestGetBaseUriRector extends AbstractRector
{
    /**
     * @var string
     */
    private const NORMALIZED_PARAMS = 'normalizedParams';

    /**
     * @readonly
     */
    private PhpDocInfoFactory $phpDocInfoFactory;

    /**
     * @readonly
     */
    private DocBlockUpdater $docBlockUpdater;

    public function __construct(PhpDocInfoFactory $phpDocInfoFactory, DocBlockUpdater $docBlockUpdater)
    {
        $this->phpDocInfoFactory = $phpDocInfoFactory;
        $this->docBlockUpdater = $docBlockUpdater;
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
     * @return Node[]|null
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

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Extbase\Mvc\Request')
        )) {
            return null;
        }

        if (! $this->isName($methodCall->name, 'getBaseUri')) {
            return null;
        }

        $globalRequestNode = new Expression($this->createGlobalRequestAssignment());
        $normalizedParamsNode = $this->createNormalizedParamsAssignment();
        $this->addPhpDocInfo($normalizedParamsNode);

        $node->expr->expr = $this->nodeFactory->createMethodCall(self::NORMALIZED_PARAMS, 'getSiteUrl');

        return [$globalRequestNode, new Expression($normalizedParamsNode), $node];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use PSR-7 compatible request for uri instead of the method getBaseUri', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$baseUri = $this->request->getBaseUri();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$request = $GLOBALS['TYPO3_REQUEST'];
/** @var NormalizedParams $normalizedParams */
$normalizedParams = $request->getAttribute('normalizedParams');
$baseUri = $normalizedParams->getSiteUrl();
CODE_SAMPLE
            ),
        ]);
    }

    private function createGlobalRequestAssignment(): Assign
    {
        return new Assign(new Variable('request'), new ArrayDimFetch(new Variable(
            Typo3NodeResolver::GLOBALS
        ), new String_('TYPO3_REQUEST')));
    }

    private function createNormalizedParamsAssignment(): Assign
    {
        return new Assign(
            new Variable(self::NORMALIZED_PARAMS),
            $this->nodeFactory->createMethodCall('request', 'getAttribute', [self::NORMALIZED_PARAMS])
        );
    }

    private function addPhpDocInfo(Assign $normalizedParamsNode): void
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($normalizedParamsNode);
        $phpDocInfo->addTagValueNode(
            new VarTagValueNode(new FullyQualifiedIdentifierTypeNode(
                'TYPO3\CMS\Core\Http\NormalizedParams'
            ), self::NORMALIZED_PARAMS, '')
        );
        $phpDocInfo->getPhpDocNode()
            ->children = [];
        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($normalizedParamsNode);
    }
}
