<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v2;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use Rector\BetterPhpDocParser\PhpDoc\ArrayItemNode;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\PhpDoc\StringNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.2/Deprecation-103965-DeprecateNamespacedShorthandValidatorUsageInExtbase.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v2\MigrateNamespacedShortHandValidatorRector\MigrateNamespacedShortHandValidatorRectorTest
 */
final class MigrateNamespacedShortHandValidatorRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private DocBlockUpdater $docBlockUpdater;

    /**
     * @readonly
     */
    private PhpDocInfoFactory $phpDocInfoFactory;

    private bool $hasChanged = false;

    public function __construct(DocBlockUpdater $docBlockUpdater, PhpDocInfoFactory $phpDocInfoFactory)
    {
        $this->docBlockUpdater = $docBlockUpdater;
        $this->phpDocInfoFactory = $phpDocInfoFactory;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate namespaced shorthand validator usage in Extbase', [new CodeSample(
            <<<'CODE_SAMPLE'
/**
 * @Extbase\Validate("TYPO3.CMS.Extbase:NotEmpty")
 */
protected $myProperty1;

/**
 * @Extbase\Validate("Vendor.Extension:Custom")
 */
protected $myProperty2;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
/**
 * @Extbase\Validate("NotEmpty")
 */
protected $myProperty1;

/**
 * @Extbase\Validate("Vendor\Extension\Validation\Validator\CustomValidator")
 */
protected $myProperty2;
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Property::class];
    }

    /**
     * @param Property $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->getDocComment() instanceof Doc) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        $this->processAnnotations($phpDocInfo);

        if (! $this->hasChanged) {
            return null;
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }

    private function processAnnotations(PhpDocInfo $phpDocInfo): void
    {
        if ($phpDocInfo->getPhpDocNode()->children === []) {
            return;
        }

        $validateAnnotations = $phpDocInfo->getByAnnotationClass('TYPO3\CMS\Extbase\Annotation\Validate');
        if (! $validateAnnotations instanceof DoctrineAnnotationTagValueNode) {
            return;
        }

        foreach ($validateAnnotations->values as $validateAnnotation) {
            if ($validateAnnotation->value === null || ! $validateAnnotation->value instanceof StringNode) {
                continue;
            }

            $this->replaceAnnotation($validateAnnotation);
        }
    }

    private function replaceAnnotation(ArrayItemNode $arrayItemNode): void
    {
        if (! $arrayItemNode->value instanceof StringNode) {
            return;
        }

        $stringNode = $arrayItemNode->value;
        $value = $stringNode->value;

        if (str_starts_with($value, 'TYPO3.CMS.Extbase:')) {
            $arrayItemNode->value = '"' . str_replace('TYPO3.CMS.Extbase:', '', $stringNode->value) . '"';
            $arrayItemNode->setAttribute(PhpDocAttributeKey::ORIG_NODE, null);
            $this->hasChanged = true;
        } elseif (str_contains($value, '.')) {
            $parts = explode(':', $value);
            if (count($parts) === 2) {
                [$packageKey, $validatorName] = $parts;
                if (str_contains($packageKey, '.')) {
                    [$vendor, $extension] = explode('.', $packageKey);
                    $arrayItemNode->value = '"' . $vendor . '\\' . $extension . '\\Validation\\Validator\\' . $validatorName . 'Validator' . '"';
                    $arrayItemNode->setAttribute(PhpDocAttributeKey::ORIG_NODE, null);
                    $this->hasChanged = true;
                }
            }
        }
    }
}
