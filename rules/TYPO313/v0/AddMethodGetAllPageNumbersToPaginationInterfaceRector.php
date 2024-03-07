<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Rector\AbstractScopeAwareRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102980-GetAllPageNumbersInPaginationInterface.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\AddMethodGetAllPageNumbersToPaginationInterfaceRector\AddMethodGetAllPageNumbersToPaginationInterfaceRectorTest
 */
final class AddMethodGetAllPageNumbersToPaginationInterfaceRector extends AbstractScopeAwareRector
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
        return new RuleDefinition('Add new method getAllPageNumbers to classes implementing PaginationInterface', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Pagination\PaginationInterface;

class MySpecialPaginationImplementingPaginationInterface implements PaginationInterface
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Pagination\PaginationInterface;

class MySpecialPaginationImplementingPaginationInterface implements PaginationInterface
{
    /**
     * @return int[]
     */
    public function getAllPageNumbers(): array
    {
        return range($this->getFirstPageNumber(), $this->getLastPageNumber());
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactorWithScope(Node $node, Scope $scope)
    {
        $classReflection = $scope->getClassReflection();
        if ($classReflection === null) {
            return null;
        }

        if (! $classReflection->implementsInterface('TYPO3\\CMS\\Core\\Pagination\\PaginationInterface')) {
            return null;
        }

        if ($node->getMethod('getAllPageNumbers') !== null) {
            return null;
        }

        $getAllPageNumbersMethod = $this->nodeFactory->createPublicMethod('getAllPageNumbers');
        $getAllPageNumbersMethod->returnType = new Node\Name('array');
        $getAllPageNumbersMethod->stmts[] = new Node\Stmt\Return_(
            $this->nodeFactory->createFuncCall('range', [
                $this->nodeFactory->createMethodCall('this', 'getFirstPageNumber'),
                $this->nodeFactory->createMethodCall('this', 'getLastPageNumber'),
            ])
        );

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($getAllPageNumbersMethod);
        $phpDocInfo->addPhpDocTagNode(
            new PhpDocTagNode('@return', new ReturnTagValueNode(new ArrayTypeNode(new IdentifierTypeNode('int')), ''))
        );

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($getAllPageNumbersMethod);

        $node->stmts[] = new Node\Stmt\Nop();
        $node->stmts[] = $getAllPageNumbersMethod;

        return $node;
    }
}
