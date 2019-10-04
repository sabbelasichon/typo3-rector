<?php
declare(strict_types=1);


namespace Ssch\TYPO3Rector\Annotation;


use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\NodeTypeResolver\Exception\MissingTagException;
use Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer\DocBlockManipulator;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\ConfiguredCodeSample;
use Rector\RectorDefinition\RectorDefinition;

final class ValidateAnnotation extends AbstractRector
{
    private const OLD_ANNOTATION = 'validate';

    public function getNodeTypes(): array
    {
        return [Property::class, ClassMethod::class];
    }

    /**
     * Process Node of matched type
     *
     * @param Node $node
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if ( ! $this->docBlockManipulator->hasTag($node, self::OLD_ANNOTATION)) {
            return null;
        }

        $tagNodes = $this->docBlockManipulator->getTagsByName($node, self::OLD_ANNOTATION);

        foreach ($tagNodes as $tagNode) {
            $explodePatternMultipleValidators = '),';

            $validators = explode($explodePatternMultipleValidators, (string) $tagNode->value);

            if(count($validators) > 1) {
                $validators[0] .= $explodePatternMultipleValidators;
            }

            foreach ($validators as $validator) {
                if ($node->getType() === 'Stmt_Property') {
                    $this->docBlockManipulator->addTag($node, $this->createPropertyAnnotation($validator));
                } elseif ($node->getType() === 'Stmt_ClassMethod') {
                    $this->docBlockManipulator->addTag($node, $this->createMethodAnnotation($validator));
                }
            }
        }

        $this->docBlockManipulator->removeTagFromNode($node, self::OLD_ANNOTATION);

        return $node;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Turns properties with `@annotation` to properties with `@newAnnotation`',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
/**
 * @validate NotEmpty
 * @validate StringLength(minimum=0, maximum=255)
 */
private $someProperty;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
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

    /**
     * @param string $validatorAnnotation
     *
     * @return PhpDocTagNode
     */
    protected function createPropertyAnnotation(string $validatorAnnotation): PhpDocTagNode
    {
        if(false !== strpos($validatorAnnotation, '(')) {
            preg_match('#(.*)\((.*)\)#', $validatorAnnotation, $matches);

            [$_, $validator, $options] = $matches;

            $optionsArray = [];
            foreach (explode(',', $options) as $option) {
                [$optionKey, $optionValue] = explode('=', $option);
                $optionsArray[] = sprintf('"%s": %s', trim($optionKey), trim($optionValue));
            }

            $annotation = sprintf('@TYPO3\\CMS\\Extbase\\Annotation\\Validate("%s", options={%s})', trim($validator), implode(',', $optionsArray));
        } else {
            $annotation = sprintf('@TYPO3\\CMS\\Extbase\\Annotation\\Validate(validator="%s")', $validatorAnnotation);
        }

        return new PhpDocTagNode($annotation, $this->createEmptyTagValueNode());
    }

    /**
     * @param string $validatorAnnotation
     *
     * @return PhpDocTagNode
     */
    private function createMethodAnnotation(string $validatorAnnotation): PhpDocTagNode
    {
        [$param, $validator] = explode(' ', $validatorAnnotation);

        $annotation = sprintf('@TYPO3\\CMS\\Extbase\\Annotation\\Validate(validator="%s", param="%s")', $validator, ltrim($param, '$'));

        return new PhpDocTagNode($annotation, $this->createEmptyTagValueNode());
    }

    /**
     * @return GenericTagValueNode
     */
    private function createEmptyTagValueNode(): GenericTagValueNode
    {
        return new GenericTagValueNode('');
    }


}
