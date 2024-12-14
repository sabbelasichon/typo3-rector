<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypeDeclaration\Property;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\StringType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Exception\ShouldNotHappenException;
use Rector\PHPStan\ScopeFetcher;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Ssch\TYPO3Rector\Contract\NoChangelogRequiredInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationWithDefaultNullRector\AddPropertyTypeDeclarationWithDefaultNullRectorTest
 */
final class AddPropertyTypeDeclarationWithDefaultNullRector extends AbstractRector implements ConfigurableRectorInterface, NoChangelogRequiredInterface, DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private StaticTypeMapper $staticTypeMapper;

    /**
     * @var AddPropertyTypeDeclaration[]
     */
    private array $addPropertyTypeDeclarations = [];

    public function __construct(StaticTypeMapper $staticTypeMapper)
    {
        $this->staticTypeMapper = $staticTypeMapper;
    }

    public function getNodeTypes(): array
    {
        return [Property::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        $configuration = [new AddPropertyTypeDeclaration('ParentClass', 'name', new StringType())];
        return new RuleDefinition(
            'Add type to property by added rules, mostly public/property by parent type with default value null',
            [
                new ConfiguredCodeSample(<<<'CODE_SAMPLE'
class SomeClass extends ParentClass
{
    public $name;
}
CODE_SAMPLE
                    , <<<'CODE_SAMPLE'
class SomeClass extends ParentClass
{
    public ?string $name = null;
}
CODE_SAMPLE
                    , $configuration),
            ]
        );
    }

    /**
     * @param Property $node
     */
    public function refactor(Node $node): ?Node
    {
        // type is already known
        if ($node->type !== null) {
            return null;
        }

        $scope = ScopeFetcher::fetch($node);
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        foreach ($this->addPropertyTypeDeclarations as $addPropertyTypeDeclaration) {
            if (! $this->isClassReflectionType($classReflection, $addPropertyTypeDeclaration->getClass())) {
                continue;
            }

            if (! $this->isName($node, $addPropertyTypeDeclaration->getPropertyName())) {
                continue;
            }

            $typeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
                $addPropertyTypeDeclaration->getType(),
                TypeKind::PROPERTY
            );
            if (! $typeNode instanceof Node) {
                // invalid configuration
                throw new ShouldNotHappenException();
            }

            $node->type = $typeNode;
            $node->props[0]->default = $this->nodeFactory->createNull();
            return $node;
        }

        return null;
    }

    /**
     * @param AddPropertyTypeDeclaration[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, AddPropertyTypeDeclaration::class);
        $this->addPropertyTypeDeclarations = $configuration;
    }

    private function isClassReflectionType(ClassReflection $classReflection, string $type): bool
    {
        if ($classReflection->hasTraitUse($type)) {
            return \true;
        }

        return $classReflection->isSubclassOf($type);
    }
}
