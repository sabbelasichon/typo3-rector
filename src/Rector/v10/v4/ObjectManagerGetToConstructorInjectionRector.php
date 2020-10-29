<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\ConfiguredCodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\Naming\Naming\PropertyNaming;
use Rector\NodeTypeResolver\Node\AttributeKey;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.4/Deprecation-90803-DeprecationOfObjectManagergetInExtbaseContext.html
 */
final class ObjectManagerGetToConstructorInjectionRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const CONTAINER_AWARE_PARENT_TYPES = '$containerAwareParentTypes';

    /**
     * @var string[]
     */
    private $containerAwareParentTypes = [ActionController::class];

    /**
     * @var PropertyNaming
     */
    private $propertyNaming;

    /**
     * @required
     */
    public function autowireAbstractToConstructorInjectionRectorDependencies(PropertyNaming $propertyNaming): void
    {
        $this->propertyNaming = $propertyNaming;
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     *
     * @throws ShouldNotHappenException
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, ObjectManagerInterface::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'get')) {
            return null;
        }

        $parentClassName = $node->getAttribute(AttributeKey::PARENT_CLASS_NAME);
        if (! in_array($parentClassName, $this->containerAwareParentTypes, true)) {
            return null;
        }

        try {
            return $this->processMethodCallNode($node);
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            return null;
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Turns fetching of dependencies via `$objectManager->get()` in Extbase ActionController to constructor injection',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
final class SomeController extends ActionController
{
    public function someAction()
    {
        // ...
        $this->objectManager->get(SomeService::class);
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeController extends ActionController
{
    /**
     * @var SomeService
     */
    private $someService;

    public function __construct(SomeService $someService)
    {
        $this->someService = $someService;
    }

    public function someAction()
    {
        // ...
    }
}
CODE_SAMPLE
                    ,
                    [
                        self::CONTAINER_AWARE_PARENT_TYPES => [ActionController::class],
                    ]
                ),
            ]
        );
    }

    public function configure(array $configuration): void
    {
        if ([] !== $configuration) {
            $this->containerAwareParentTypes = $configuration[self::CONTAINER_AWARE_PARENT_TYPES] ?? [];
        }
    }

    private function processMethodCallNode(MethodCall $methodCall): ?Node
    {
        $serviceType = $this->getServiceTypeFromMethodCallArgument($methodCall);
        if (! $serviceType instanceof ObjectType) {
            return null;
        }

        $classLike = $methodCall->getAttribute(AttributeKey::CLASS_NODE);
        if (! $classLike instanceof Class_) {
            throw new ShouldNotHappenException();
        }

        $propertyName = $this->propertyNaming->fqnToVariableName($serviceType);

        $this->addConstructorDependencyToClass($classLike, $serviceType, $propertyName);

        return $this->createPropertyFetch('this', $propertyName);
    }

    private function getServiceTypeFromMethodCallArgument(MethodCall $methodCallNode): Type
    {
        if (! isset($methodCallNode->args[0])) {
            return new MixedType();
        }

        $argument = $methodCallNode->args[0]->value;

        if ($argument instanceof ClassConstFetch && $argument->class instanceof Name) {
            $className = $this->getName($argument->class);

            if(!is_string($className)) {
                return new MixedType();
            }

            return new ObjectType($className);
        }

        return new MixedType();
    }
}
