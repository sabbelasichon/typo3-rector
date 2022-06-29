<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\ValueObject\Type\FullyQualifiedIdentifierTypeNode;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/11.3/Deprecation-94223-ExtbaseRequest-getBaseUri.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v3\SubstituteExtbaseRequestGetBaseUriRector\SubstituteExtbaseRequestGetBaseUriRectorTest
 */
final class SubstituteExtbaseRequestGetBaseUriRector extends AbstractRector
{
    /**
     * @var string
     */
    private const NORMALIZED_PARAMS = 'normalizedParams';

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Extbase\Mvc\Request')
        )) {
            return null;
        }

        if (! $this->isName($node->name, 'getBaseUri')) {
            return null;
        }

        $globalRequestNode = $this->createGlobalRequestAssignment();
        $normalizedParamsNode = $this->createNormalizedParamsAssignment();
        $this->addPhpDocInfo($normalizedParamsNode);

        $this->nodesToAddCollector->addNodesBeforeNode([$globalRequestNode, $normalizedParamsNode], $node);

        return $this->nodeFactory->createMethodCall(self::NORMALIZED_PARAMS, 'getSiteUrl');
    }

    /**
     * @codeCoverageIgnore
     */
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
        $phpDocInfo->makeSingleLined();
    }
}
