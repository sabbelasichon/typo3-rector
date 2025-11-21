<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Comment;
use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Cast\String_;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use Rector\NodeManipulator\ClassDependencyManipulator;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PHPStan\ScopeFetcher;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersion;
use Rector\ValueObject\PhpVersionFeature;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107537-CreateVersionNumberedFileName.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateGeneralUtilityCreateVersionNumberedFilenameRector\MigrateGeneralUtilityCreateVersionNumberedFilenameRectorTest
 */
final class MigrateGeneralUtilityCreateVersionNumberedFilenameRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private ClassDependencyManipulator $classDependencyManipulator;

    /**
     * @readonly
     */
    private Typo3GlobalsFactory $typo3GlobalsFactory;

    public function __construct(
        ClassDependencyManipulator $classDependencyManipulator,
        Typo3GlobalsFactory $typo3GlobalsFactory
    ) {
        $this->classDependencyManipulator = $classDependencyManipulator;
        $this->typo3GlobalsFactory = $typo3GlobalsFactory;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `GeneralUtility::createVersionNumberedFilename()`', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

public function renderUrl(string $file): string
{
    $file = GeneralUtility::getFileAbsFileName($file);
    $partialUrl = GeneralUtility::createVersionNumberedFilename($file);
    return PathUtility::getAbsoluteWebPath($partialUrl);
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\SystemResource\Publishing\SystemResourcePublisherInterface;
use TYPO3\CMS\Core\SystemResource\Publishing\UriGenerationOptions;
use TYPO3\CMS\Core\SystemResource\SystemResourceFactory;

public function __construct(
    private readonly SystemResourceFactory $systemResourceFactory,
    private readonly SystemResourcePublisherInterface $resourcePublisher,
) {}

public function renderUrl(string $file): string
{
    $resource = $this->systemResourceFactory->createPublicResource($file);
    return (string)$this->resourcePublisher->generateUri(
        $resource,
        $GLOBALS['TYPO3_REQUEST'],
        new UriGenerationOptions(absoluteUri: true),
    );
}
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $hasChanged = false;

        $this->traverseNodesWithCallable($node->stmts, function (Node $stmt) use (&$hasChanged): ?array {
            $staticCall = null;
            $isReturn = false;
            $assignNode = null;

            if ($stmt instanceof Expression && $stmt->expr instanceof Assign) {
                if ($stmt->expr->expr instanceof StaticCall) {
                    $staticCall = $stmt->expr->expr;
                    $assignNode = $stmt->expr;
                }
            } elseif ($stmt instanceof Return_ && $stmt->expr instanceof StaticCall) {
                $staticCall = $stmt->expr;
                $isReturn = true;
            }

            if (! $staticCall instanceof StaticCall) {
                return null;
            }

            if (! $this->isName($staticCall->name, 'createVersionNumberedFilename')) {
                return null;
            }

            if (! $this->isObjectType($staticCall->class, new ObjectType('TYPO3\CMS\Core\Utility\GeneralUtility'))) {
                return null;
            }

            $fileArgument = $staticCall->getArgs()[0] ?? null;
            if ($fileArgument === null) {
                return null;
            }

            $scope = ScopeFetcher::fetch($stmt);

            $hasChanged = true;

            // Create: $resource = $this->systemResourceFactory->createPublicResource($file);
            $resourceFactoryFetch = $this->nodeFactory->createPropertyFetch('this', 'systemResourceFactory');
            $createPublicResourceCall = $this->nodeFactory->createMethodCall(
                $resourceFactoryFetch,
                'createPublicResource',
                [$fileArgument]
            );
            $resourceVar = new Variable('resource');
            $resourceAssign = new Assign($resourceVar, $createPublicResourceCall);
            $resourceAssignStmt = new Expression($resourceAssign);

            // Create: (string)$this->resourcePublisher->generateUri(...);
            $requestVar = $this->getTYPO3RequestInScope($scope);
            $resourcePublisherFetch = $this->nodeFactory->createPropertyFetch('this', 'resourcePublisher');

            if (\PHP_VERSION_ID >= PhpVersion::PHP_80) {
                $uriGenOptionsArg = new Arg($this->nodeFactory->createTrue(), false, false, [], new Identifier(
                    'absoluteUri'
                ));
                $uriGenOptionsArgs = [$uriGenOptionsArg];
            } else {
                $uriGenOptionsArgs = [];
                $uriGenOptionsArgs[] = new Arg($this->nodeFactory->createNull());
                $uriGenOptionsArgs[] = new Arg($this->nodeFactory->createTrue());
            }

            $uriGenOptions = new New_(new FullyQualified(
                'TYPO3\CMS\Core\SystemResource\Publishing\UriGenerationOptions'
            ), $uriGenOptionsArgs);

            $generateUriCall = $this->nodeFactory->createMethodCall($resourcePublisherFetch, 'generateUri', [
                new Arg($resourceVar),
                new Arg($requestVar),
                new Arg($uriGenOptions),
            ]);

            $replacementNode = new String_($generateUriCall);

            $comments = [
                new Comment(
                    "// TODO from Rector: Remove 'GeneralUtility::getFileAbsFileName()' and 'PathUtility::getAbsoluteWebPath()' yourself."
                ),
                new Comment(
                    '// TODO from Rector: See https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107537-CreateVersionNumberedFileName.html#migration'
                ),
            ];

            // Reconstruct the original statement type (Return or Assignment)
            if ($isReturn) {
                $finalStmt = new Return_($replacementNode, [
                    AttributeKey::COMMENTS => $comments,
                ]);
            } else {
                // Re-use the original variable for assignment
                /** @var Assign $assignNode */
                $uriAssign = new Assign($assignNode->var, $replacementNode);
                $finalStmt = new Expression($uriAssign, [
                    AttributeKey::COMMENTS => $comments,
                ]);
            }

            return [$resourceAssignStmt, $finalStmt];
        });

        if ($hasChanged) {
            $this->addDependency($node, 'systemResourceFactory', 'TYPO3\CMS\Core\SystemResource\SystemResourceFactory');
            $this->addDependency(
                $node,
                'resourcePublisher',
                'TYPO3\CMS\Core\SystemResource\Publishing\SystemResourcePublisherInterface'
            );
            return $node;
        }

        return null;
    }

    /**
     * @return ArrayDimFetch|PropertyFetch
     */
    private function getTYPO3RequestInScope(Scope $scope)
    {
        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection
            && $classReflection->is('TYPO3\CMS\Extbase\Mvc\Controller\ActionController')
        ) {
            return $this->nodeFactory->createPropertyFetch('this', 'request');
        }

        return $this->typo3GlobalsFactory->create('TYPO3_REQUEST');
    }

    /**
     * Adds a dependency using the ClassDependencyManipulator.
     */
    private function addDependency(Class_ $classNode, string $propertyName, string $className): void
    {
        if (\PHP_VERSION_ID >= PhpVersionFeature::READONLY_PROPERTY) {
            $flags = Modifiers::PRIVATE | Modifiers::READONLY;
        } else {
            $flags = Modifiers::PRIVATE;
        }

        $propertyMetadata = new PropertyMetadata($propertyName, new ObjectType($className), $flags);

        $this->classDependencyManipulator->addConstructorDependency($classNode, $propertyMetadata);
    }
}
