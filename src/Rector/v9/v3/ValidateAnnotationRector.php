<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v3;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\ImportExtbaseAnnotationIfMissingFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.3/Deprecation-83167-ReplaceValidateWithTYPO3CMSExtbaseAnnotationValidate.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v3\ValidateAnnotationRector\ValidateAnnotationRectorTest
 */
final class ValidateAnnotationRector extends AbstractRector
{
    /**
     * @var string
     */
    private const OLD_ANNOTATION = 'validate';

    /**
     * @readonly
     */
    private PhpDocTagRemover $phpDocTagRemover;

    /**
     * @readonly
     */
    private ImportExtbaseAnnotationIfMissingFactory $importExtbaseAnnotationIfMissingFactory;

    public function __construct(
        PhpDocTagRemover $phpDocTagRemover,
        ImportExtbaseAnnotationIfMissingFactory $importExtbaseAnnotationIfMissingFactory
    ) {
        $this->phpDocTagRemover = $phpDocTagRemover;
        $this->importExtbaseAnnotationIfMissingFactory = $importExtbaseAnnotationIfMissingFactory;
    }

    /**
     * @return array<class-string<Node>>
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
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        if (! $phpDocInfo->hasByName(self::OLD_ANNOTATION)) {
            return null;
        }

        $tagNodes = $phpDocInfo->getTagsByName(self::OLD_ANNOTATION);

        foreach ($tagNodes as $tagNode) {
            if (! property_exists($tagNode, 'value')) {
                continue;
            }

            $validators = preg_split('#[,](?![^(]*\))#', (string) $tagNode->value);

            if (! is_array($validators)) {
                continue;
            }

            $validators = array_map('trim', $validators);

            foreach ($validators as $validator) {
                if ($node instanceof Property) {
                    $phpDocInfo->addPhpDocTagNode($this->createPropertyAnnotation($validator));
                } elseif ($node instanceof ClassMethod) {
                    $phpDocInfo->addPhpDocTagNode($this->createMethodAnnotation($validator));
                }
            }
        }

        $this->importExtbaseAnnotationIfMissingFactory->addExtbaseAliasAnnotationIfMissing($node);

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
                new CodeSample(
                    <<<'CODE_SAMPLE'
/**
 * @validate NotEmpty
 * @validate StringLength(minimum=0, maximum=255)
 */
private $someProperty;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Annotation as Extbase;
/**
 * @Extbase\Validate("NotEmpty")
 * @Extbase\Validate("StringLength", options={"minimum": 3, "maximum": 50})
 */
private $someProperty;
CODE_SAMPLE
                ),
            ]
        );
    }

    private function createPropertyAnnotation(string $validatorAnnotation): PhpDocTagNode
    {
        if (str_contains($validatorAnnotation, '(')) {
            preg_match_all(
                '#(?P<validatorName>.*)(?<=[\w])\s*\((?P<validatorOptions>.*)\)#',
                $validatorAnnotation,
                $matches
            );

            $validator = $matches['validatorName'][0];
            $options = $matches['validatorOptions'][0];

            preg_match_all(
                '#\s*(?P<optionName>[a-z0-9]+)\s*=\s*(?P<optionValue>"(?:"|[^"])*"|\'(?:\\\\\'|[^\'])*\'|(?:\s|[^,"\']*))#ixS',
                (string) $options,
                $optionNamesValues
            );

            $optionNames = $optionNamesValues['optionName'];
            $optionValues = $optionNamesValues['optionValue'];

            $optionsArray = [];
            foreach ($optionNames as $key => $optionName) {
                $optionValue = $optionValues[$key];
                if (! is_string($optionValue)) {
                    continue;
                }

                $optionValue = str_replace("'", '"', $optionValue);
                $optionsArray[] = sprintf('"%s": %s', trim((string) $optionName), trim($optionValue));
            }

            $annotation = sprintf(
                '@Extbase\Validate("%s", options={%s})',
                trim((string) $validator),
                implode(', ', $optionsArray)
            );
        } else {
            $annotation = sprintf('@Extbase\Validate("%s")', $validatorAnnotation);
        }

        return new PhpDocTagNode($annotation, $this->createEmptyTagValueNode());
    }

    private function createMethodAnnotation(string $validatorAnnotation): PhpDocTagNode
    {
        [$param, $validator] = explode(' ', $validatorAnnotation);
        $annotation = sprintf('@Extbase\Validate("%s", param="%s")', $validator, ltrim($param, '$'));

        return new PhpDocTagNode($annotation, $this->createEmptyTagValueNode());
    }

    private function createEmptyTagValueNode(): GenericTagValueNode
    {
        return new GenericTagValueNode('');
    }
}
