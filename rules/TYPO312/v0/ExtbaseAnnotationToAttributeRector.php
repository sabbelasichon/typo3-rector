<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Use_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\Php80\NodeFactory\AttrGroupsFactory;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Php80\ValueObject\DoctrineTagAndAnnotationToAttribute;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Ssch\TYPO3Rector\TYPO312\AnnotationToAttribute\AttributeDecorator;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-96688-AttributesForExtbaseAnnotations.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ExtbaseAnnotationToAttributeRector\ExtbaseAnnotationToAttributeRectorTest
 */
final class ExtbaseAnnotationToAttributeRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @readonly
     */
    private PhpDocTagRemover $phpDocTagRemover;

    /**
     * @readonly
     */
    private UseImportsResolver $useImportsResolver;

    /**
     * @readonly
     */
    private PhpAttributeAnalyzer $phpAttributeAnalyzer;

    /**
     * @readonly
     */
    private DocBlockUpdater $docBlockUpdater;

    /**
     * @readonly
     */
    private PhpDocInfoFactory $phpDocInfoFactory;

    /**
     * @readonly
     */
    private AttrGroupsFactory $attrGroupsFactory;

    /**
     * @var AnnotationToAttribute[]
     */
    private array $annotationsToAttributes = [];

    /**
     * @readonly
     */
    private AttributeDecorator $attributeDecorator;

    public function __construct(
        AttributeDecorator $attributeDecorator,
        AttrGroupsFactory $attrGroupsFactory,
        PhpDocTagRemover $phpDocTagRemover,
        UseImportsResolver $useImportsResolver,
        PhpAttributeAnalyzer $phpAttributeAnalyzer,
        DocBlockUpdater $docBlockUpdater,
        PhpDocInfoFactory $phpDocInfoFactory
    ) {
        $this->annotationsToAttributes = [
            new AnnotationToAttribute('TYPO3\CMS\Extbase\Annotation\ORM\Lazy'),
            new AnnotationToAttribute('TYPO3\CMS\Extbase\Annotation\ORM\Transient'),
            new AnnotationToAttribute('TYPO3\CMS\Extbase\Annotation\ORM\Cascade'),
            new AnnotationToAttribute('TYPO3\CMS\Extbase\Annotation\IgnoreValidation'),
            new AnnotationToAttribute('TYPO3\CMS\Extbase\Annotation\Validate'),
            new AnnotationToAttribute('Extbase\ORM\Transient'),
            new AnnotationToAttribute('Extbase\ORM\Lazy'),
            new AnnotationToAttribute('Extbase\ORM\Cascade'),
            new AnnotationToAttribute('Extbase\IgnoreValidation'),
            new AnnotationToAttribute('Extbase\Validate'),
        ];

        $this->phpDocTagRemover = $phpDocTagRemover;
        $this->useImportsResolver = $useImportsResolver;
        $this->phpAttributeAnalyzer = $phpAttributeAnalyzer;
        $this->docBlockUpdater = $docBlockUpdater;
        $this->phpDocInfoFactory = $phpDocInfoFactory;
        $this->attrGroupsFactory = $attrGroupsFactory;
        $this->attributeDecorator = $attributeDecorator;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change annotation to attribute', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Annotation as Extbase;

class MyEntity
{
    /**
    * @Extbase\ORM\Lazy()
    * @Extbase\ORM\Transient()
    */
    protected string $myProperty
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Annotation as Extbase;

class MyEntity
{
    #[Extbase\ORM\Lazy()]
    #[Extbase\ORM\Transient()]
    protected string $myProperty
}
CODE_SAMPLE
        )]);
    }

    public function getNodeTypes(): array
    {
        return [Property::class, ClassMethod::class];
    }

    /**
     * @param Property|ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        $uses = $this->useImportsResolver->resolveBareUses();
        $annotationAttributeGroups = $this->processDoctrineAnnotationClasses($phpDocInfo, $uses);
        if ($annotationAttributeGroups === []) {
            return null;
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        foreach ($annotationAttributeGroups as $attributeGroup) {
            foreach ($attributeGroup->attrs as $attr) {
                $phpAttributeName = $attr->name->getAttribute(AttributeKey::PHP_ATTRIBUTE_NAME);
                $this->attributeDecorator->decorate($phpAttributeName, $attr);
            }
        }

        $node->attrGroups = \array_merge($node->attrGroups, $annotationAttributeGroups);
        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ATTRIBUTES;
    }

    /**
     * @param Use_[] $uses
     * @return AttributeGroup[]
     */
    private function processDoctrineAnnotationClasses(PhpDocInfo $phpDocInfo, array $uses): array
    {
        if ($phpDocInfo->getPhpDocNode()->children === []) {
            return [];
        }

        $doctrineTagAndAnnotationToAttributes = [];
        $doctrineTagValueNodes = [];
        foreach ($phpDocInfo->getPhpDocNode()->children as $phpDocChildNode) {
            if (! $phpDocChildNode instanceof PhpDocTagNode) {
                continue;
            }

            if (! $phpDocChildNode->value instanceof DoctrineAnnotationTagValueNode) {
                continue;
            }

            $doctrineTagValueNode = $phpDocChildNode->value;
            $annotationToAttribute = $this->matchAnnotationToAttribute($doctrineTagValueNode);
            if (! $annotationToAttribute instanceof AnnotationToAttribute) {
                continue;
            }

            // Fix the missing leading slash in most of the wild use cases
            if (str_starts_with($doctrineTagValueNode->identifierTypeNode->name, '@TYPO3\CMS')) {
                $doctrineTagValueNode->identifierTypeNode->name = str_replace(
                    '@TYPO3\CMS',
                    '@\\TYPO3\CMS',
                    $doctrineTagValueNode->identifierTypeNode->name
                );
            }

            $doctrineTagAndAnnotationToAttributes[] = new DoctrineTagAndAnnotationToAttribute(
                $doctrineTagValueNode,
                $annotationToAttribute
            );
            $doctrineTagValueNodes[] = $doctrineTagValueNode;
        }

        $attributeGroups = $this->attrGroupsFactory->create($doctrineTagAndAnnotationToAttributes, $uses);
        if ($this->phpAttributeAnalyzer->hasRemoveArrayState($attributeGroups)) {
            return [];
        }

        foreach ($doctrineTagValueNodes as $doctrineTagValueNode) {
            $this->phpDocTagRemover->removeTagValueFromNode($phpDocInfo, $doctrineTagValueNode);
        }

        return $attributeGroups;
    }

    private function matchAnnotationToAttribute(
        DoctrineAnnotationTagValueNode $doctrineAnnotationTagValueNode
    ): ?AnnotationToAttribute {
        foreach ($this->annotationsToAttributes as $annotationToAttribute) {
            if (! $doctrineAnnotationTagValueNode->hasClassName($annotationToAttribute->getTag())) {
                continue;
            }

            return $annotationToAttribute;
        }

        return null;
    }
}
