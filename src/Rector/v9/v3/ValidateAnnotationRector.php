<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v3;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.3/Deprecation-83167-ReplaceValidateWithTYPO3CMSExtbaseAnnotationValidate.html
 */
final class ValidateAnnotationRector extends AbstractRector
{
    /**
     * @var string
     */
    private const OLD_ANNOTATION = 'validate';

    /**
     * @var PhpDocTagRemover
     */
    private $phpDocTagRemover;

    public function __construct(PhpDocTagRemover $phpDocTagRemover)
    {
        $this->phpDocTagRemover = $phpDocTagRemover;
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [Property::class, ClassMethod::class];
    }

    /**
     * @param Property|ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if (null === $phpDocInfo) {
            return null;
        }
        if (! $phpDocInfo->hasByName(self::OLD_ANNOTATION)) {
            return null;
        }
        $tagNodes = $phpDocInfo->getTagsByName(self::OLD_ANNOTATION);
        foreach ($tagNodes as $tagNode) {
            $explodePatternMultipleValidators = '),';

            if (! property_exists($tagNode, 'value')) {
                continue;
            }

            $validators = explode($explodePatternMultipleValidators, (string) $tagNode->value);
            if (count($validators) > 1) {
                $validators[0] .= $explodePatternMultipleValidators;
            }
            foreach ($validators as $validator) {
                if ($node instanceof Property) {
                    $phpDocInfo->addPhpDocTagNode($this->createPropertyAnnotation($validator));
                } elseif ($node instanceof ClassMethod) {
                    $phpDocInfo->addPhpDocTagNode($this->createMethodAnnotation($validator));
                }
            }
        }
        $this->phpDocTagRemover->removeByName($phpDocInfo, self::OLD_ANNOTATION);
        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Turns properties with `@validate` to properties with `@TYPO3\CMS\Extbase\Annotation\Validate`',
            [
                new CodeSample(<<<'CODE_SAMPLE'
/**
 * @validate NotEmpty
 * @validate StringLength(minimum=0, maximum=255)
 */
private $someProperty;
CODE_SAMPLE
, <<<'CODE_SAMPLE'
/**
 * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
 * @TYPO3\CMS\Extbase\Annotation\Validate("StringLength", options={"minimum": 3, "maximum": 50})
 */
private $someProperty;
CODE_SAMPLE
),
            ]
        );
    }

    private function createPropertyAnnotation(string $validatorAnnotation): PhpDocTagNode
    {
        if (false !== strpos($validatorAnnotation, '(')) {
            preg_match_all('#(?P<validatorName>.*)\((?P<validatorOptions>.*)\)#', $validatorAnnotation, $matches);

            $validator = $matches['validatorName'][0];
            $options = $matches['validatorOptions'][0];

            preg_match_all(
                '#\s*(?P<optionName>[a-z0-9]+)\s*=\s*(?P<optionValue>"(?:\\\"|[^"])*"|\'(?:\\\\\'|[^\'])*\'|(?:\s|[^,"\']*))#ixS',
                $options,
                $optionNamesValues
            );

            $optionNames = $optionNamesValues['optionName'];
            $optionValues = $optionNamesValues['optionValue'];

            $optionsArray = [];
            foreach ($optionNames as $key => $optionName) {
                $optionsArray[] = sprintf('"%s": %s', trim($optionName), trim($optionValues[$key]));
            }

            $annotation = sprintf(
                '@TYPO3\CMS\Extbase\Annotation\Validate("%s", options={%s})',
                trim($validator),
                implode(',', $optionsArray)
            );
        } else {
            $annotation = sprintf('@TYPO3\CMS\Extbase\Annotation\Validate(validator="%s")', $validatorAnnotation);
        }
        return new AttributeAwarePhpDocTagNode($annotation, $this->createEmptyTagValueNode());
    }

    private function createMethodAnnotation(string $validatorAnnotation): PhpDocTagNode
    {
        [$param, $validator] = explode(' ', $validatorAnnotation);
        $annotation = sprintf(
            '@TYPO3\CMS\Extbase\Annotation\Validate(validator="%s", param="%s")',
            $validator,
            ltrim($param, '$')
        );
        return new AttributeAwarePhpDocTagNode($annotation, $this->createEmptyTagValueNode());
    }

    private function createEmptyTagValueNode(): GenericTagValueNode
    {
        return new GenericTagValueNode('');
    }
}
