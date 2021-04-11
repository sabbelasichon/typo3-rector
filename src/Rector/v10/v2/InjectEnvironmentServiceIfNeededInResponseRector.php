<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v2;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ObjectType;
use PHPStan\Type\VerbosityLevel;
use Rector\Core\NodeManipulator\ClassInsertManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Symplify\Astral\ValueObject\NodeBuilder\MethodBuilder;
use Symplify\Astral\ValueObject\NodeBuilder\ParamBuilder;
use Symplify\Astral\ValueObject\NodeBuilder\PropertyBuilder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Extbase\Mvc\Web\Response;
use TYPO3\CMS\Extbase\Service\EnvironmentService;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.2/Deprecation-89468-DeprecateInjectionOfEnvironmentServiceInWebRequest.html
 *
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v2\InjectEnvironmentServiceIfNeededInResponse\InjectEnvironmentServiceIfNeededInResponseRectorTest
 */
final class InjectEnvironmentServiceIfNeededInResponseRector extends AbstractRector
{
    /**
     * @var string
     */
    private const ENVIRONMENT_SERVICE = 'environmentService';

    /**
     * @var ClassInsertManipulator
     */
    private $classInsertManipulator;

    public function __construct(ClassInsertManipulator $classInsertManipulator)
    {
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
        if (! $this->isObjectType($node, new ObjectType(Response::class))) {
            return null;
        }
        if (! $this->isPropertyEnvironmentServiceInUse($node)) {
            return null;
        }

        $this->addInjectEnvironmentServiceMethod($node);
        $this->classInsertManipulator->addAsFirstMethod($node, $this->createEnvironmentServiceProperty());
        $this->classInsertManipulator->addAsFirstMethod($node, new Nop());

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Inject EnvironmentService if needed in subclass of Response', [
            new CodeSample(<<<'CODE_SAMPLE'
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
, <<<'CODE_SAMPLE'
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
        $propertyBuilder = new PropertyBuilder(self::ENVIRONMENT_SERVICE);
        $propertyBuilder->makeProtected();

        $type = new FullyQualifiedObjectType(EnvironmentService::class);
        $propertyBuilder->setDocComment(
            new Doc(sprintf('/**%s * @var \%s%s */', PHP_EOL, $type->describe(VerbosityLevel::typeOnly()), PHP_EOL))
        );

        return $propertyBuilder->getNode();
    }

    private function isPropertyEnvironmentServiceInUse(Class_ $node): bool
    {
        $isEnvironmentServicePropertyUsed = false;
        $this->traverseNodesWithCallable($node->stmts, function (Node $node) use (
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

    private function addInjectEnvironmentServiceMethod(Class_ $node): void
    {
        $paramBuilder = new ParamBuilder(self::ENVIRONMENT_SERVICE);
        $paramBuilder->setType(new FullyQualified(EnvironmentService::class));

        $param = $paramBuilder->getNode();
        $propertyAssignNode = $this->nodeFactory->createPropertyAssignmentWithExpr(
            self::ENVIRONMENT_SERVICE,
            new Variable(self::ENVIRONMENT_SERVICE)
        );

        $classMethodBuilder = new MethodBuilder('injectEnvironmentService');
        $classMethodBuilder->addParam($param);
        $classMethodBuilder->addStmt($propertyAssignNode);
        $classMethodBuilder->makePublic();
        $node->stmts[] = new Nop();
        $node->stmts[] = $classMethodBuilder->getNode();
    }
}
