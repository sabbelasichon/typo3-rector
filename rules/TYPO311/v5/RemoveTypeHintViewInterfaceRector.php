<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v5;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\ValueObject\Type\FullyQualifiedIdentifierTypeNode;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.5/Deprecation-95222-ExtbaseViewInterface.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v5\RemoveTypeHintViewInterfaceRector\RemoveTypeHintViewInterfaceRectorTest
 */
final class RemoveTypeHintViewInterfaceRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private DocBlockUpdater $docBlockUpdater;

    /**
     * @readonly
     */
    private PhpDocInfoFactory $phpDocInfoFactory;

    public function __construct(DocBlockUpdater $docBlockUpdater, PhpDocInfoFactory $phpDocInfoFactory)
    {
        $this->docBlockUpdater = $docBlockUpdater;
        $this->phpDocInfoFactory = $phpDocInfoFactory;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove', [new CodeSample(
            <<<'CODE_SAMPLE'
protected function initializeView(ViewInterface $view)
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
protected function initializeView($view)
CODE_SAMPLE
        )]);
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

        $param = $node->getParams()[0];
        $param->type = null;

        $this->decorateParamDocType($param, $node);

        return $node;
    }

    private function shouldSkip(ClassMethod $classMethod): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $classMethod,
            new ObjectType('TYPO3\CMS\Extbase\Mvc\Controller\ActionController')
        )) {
            return true;
        }

        if (! $this->isName($classMethod->name, 'initializeView')) {
            return true;
        }

        $params = $classMethod->getParams();
        if ($params === []) {
            return true;
        }

        /** @var Param $firstParam */
        $firstParam = $params[0];
        if ($firstParam->type === null) {
            return true;
        }

        $type = $firstParam->type;
        return $type instanceof FullyQualified && ! $this->isName($type, 'TYPO3\CMS\Extbase\Mvc\View\ViewInterface');
    }

    /**
     * @see \Rector\DowngradePhp80\Rector\Enum_\DowngradeEnumToConstantListClassRector::decorateParamDocType
     */
    private function decorateParamDocType(Param $param, ClassMethod $classMethod): void
    {
        $paramName = '$' . $this->getName($param);

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
        $phpDocInfo->addTagValueNode(
            new ParamTagValueNode(
                new FullyQualifiedIdentifierTypeNode('TYPO3\CMS\Extbase\Mvc\View\ViewInterface'),
                \false,
                $paramName,
                '',
                false
            )
        );
        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($classMethod);
    }
}
