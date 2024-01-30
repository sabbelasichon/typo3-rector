<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\TYPO310\v2;

use PhpParser\Builder\Method;
use PhpParser\Builder\Param;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ObjectType;
use PHPStan\Type\VerbosityLevel;
use Rector\NodeManipulator\ClassInsertManipulator;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.2/Deprecation-89468-DeprecateInjectionOfEnvironmentServiceInWebRequest.html
 *
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v2\InjectEnvironmentServiceIfNeededInResponseRector\InjectEnvironmentServiceIfNeededInResponseRectorTest
 */
final class InjectEnvironmentServiceIfNeededInResponseRector extends AbstractRector
{
    /**
     * @var string
     */
    private const ENVIRONMENT_SERVICE = 'environmentService';

    /**
     * @readonly
     */
    private ClassInsertManipulator $classInsertManipulator;

    public function __construct(
        ClassInsertManipulator $classInsertManipulator
    ) {
        $this->classInsertManipulator = $classInsertManipulator;
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
        if (! $this->isObjectType($node, new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Response'))) {
            return null;
        }

        if (! $this->isPropertyEnvironmentServiceInUse($node)) {
            return null;
        }

        // already added
        $classMethod = $node->getMethod('injectEnvironmentService');
        if ($classMethod instanceof ClassMethod) {
            return null;
        }

        $this->addInjectEnvironmentServiceMethod($node);
        $property = $this->createEnvironmentServiceProperty();
        $this->classInsertManipulator->addAsFirstMethod($node, $property);

        return $node;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Inject EnvironmentService if needed in subclass of Response', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class MyResponse extends Response
{
    public function myMethod()
    {
        if ($this->environmentService->isEnvironmentInCliMode()) {

        }
    }
}

class MyOtherResponse extends Response
{
    public function myMethod()
    {

    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class MyResponse extends Response
{
    /**
     * @var \TYPO3\CMS\Extbase\Service\EnvironmentService
     */
    protected $environmentService;

    public function myMethod()
    {
        if ($this->environmentService->isEnvironmentInCliMode()) {

        }
    }

    public function injectEnvironmentService(\TYPO3\CMS\Extbase\Service\EnvironmentService $environmentService)
    {
        $this->environmentService = $environmentService;
    }
}

class MyOtherResponse extends Response
{
    public function myMethod()
    {

    }
}
CODE_SAMPLE
            ),
        ]);
    }

    private function createEnvironmentServiceProperty(): Property
    {
        $propertyBuilder = new \PhpParser\Builder\Property(self::ENVIRONMENT_SERVICE);
        $propertyBuilder->makeProtected();

        $type = new FullyQualifiedObjectType('TYPO3\CMS\Extbase\Service\EnvironmentService');
        $propertyBuilder->setDocComment(
            new Doc(sprintf('/**%s * @var \%s%s */', PHP_EOL, $type->describe(VerbosityLevel::typeOnly()), PHP_EOL))
        );

        return $propertyBuilder->getNode();
    }

    private function isPropertyEnvironmentServiceInUse(Class_ $class): bool
    {
        $isEnvironmentServicePropertyUsed = false;
        $this->traverseNodesWithCallable($class->stmts, function (Node $node) use (
            &$isEnvironmentServicePropertyUsed
        ): ?PropertyFetch {
            if (! $node instanceof PropertyFetch) {
                return null;
            }

            if ($this->isName($node->name, 'environmentService')) {
                $isEnvironmentServicePropertyUsed = true;
            }

            return $node;
        });
        return $isEnvironmentServicePropertyUsed;
    }

    private function addInjectEnvironmentServiceMethod(Class_ $class): void
    {
        $paramBuilder = new Param(self::ENVIRONMENT_SERVICE);
        $paramBuilder->setType(new FullyQualified('TYPO3\CMS\Extbase\Service\EnvironmentService'));

        $param = $paramBuilder->getNode();
        $propertyAssignNode = $this->nodeFactory->createPropertyAssignmentWithExpr(
            self::ENVIRONMENT_SERVICE,
            new Variable(self::ENVIRONMENT_SERVICE)
        );

        $classMethodBuilder = new Method('injectEnvironmentService');
        $classMethodBuilder->addParam($param);
        $classMethodBuilder->addStmt($propertyAssignNode);
        $classMethodBuilder->makePublic();
        $class->stmts[] = new Nop();
        $class->stmts[] = $classMethodBuilder->getNode();
    }
}
