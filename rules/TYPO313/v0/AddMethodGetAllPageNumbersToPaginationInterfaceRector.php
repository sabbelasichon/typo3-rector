<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Return_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\Reflection\ClassReflection;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102980-GetAllPageNumbersInPaginationInterface.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\AddMethodGetAllPageNumbersToPaginationInterfaceRector\AddMethodGetAllPageNumbersToPaginationInterfaceRectorTest
 */
final class AddMethodGetAllPageNumbersToPaginationInterfaceRector extends AbstractRector implements DocumentedRuleInterface
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
    public function refactor(Node $node): ?Node
    {
        if (! $this->implementsPaginationInterface($node)) {
            return null;
        }

        if ($node->getMethod('getAllPageNumbers') !== null) {
            return null;
        }

        $getAllPageNumbersMethod = $this->nodeFactory->createPublicMethod('getAllPageNumbers');
        $getAllPageNumbersMethod->returnType = new Name('array');
        $getAllPageNumbersMethod->stmts[] = new Return_(
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

        $node->stmts[] = new Nop();
        $node->stmts[] = $getAllPageNumbersMethod;

        return $node;
    }

    private function implementsPaginationInterface(Class_ $node): bool
    {
        $scope = ScopeFetcher::fetch($node);

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        return $classReflection->implementsInterface('TYPO3\CMS\Core\Pagination\PaginationInterface');
    }
}
