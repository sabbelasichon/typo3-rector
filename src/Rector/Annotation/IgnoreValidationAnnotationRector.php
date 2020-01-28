<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Annotation;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\NodeTypeResolver\Exception\MissingTagException;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

final class IgnoreValidationAnnotationRector extends AbstractRector
{
    /**
     * @var string
     */
    private $oldAnnotation = 'ignorevalidation';

    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * Process Node of matched type.
     *
     * @param Node $node
     *
     * @throws MissingTagException
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->docBlockManipulator->hasTag($node, $this->oldAnnotation)) {
            return null;
        }

        $tagNode = $this->docBlockManipulator->getTagByName($node, $this->oldAnnotation);

        $this->docBlockManipulator->addTag($node, new PhpDocTagNode('@TYPO3\CMS\Extbase\Annotation\IgnoreValidation("' . ltrim((string) $tagNode->value, '$') . '")', new GenericTagValueNode('')));
        $this->docBlockManipulator->removeTagFromNode($node, $this->oldAnnotation);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Turns properties with `@ignorevalidation` to properties with `@TYPO3\CMS\Extbase\Annotation\IgnoreValidation`',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
/**
 * @ignorevalidation $param
 */
public function method($param)
{
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
/**
 * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("param")
 */
public function method($param)
{
}
CODE_SAMPLE
                ),
            ]
        );
    }
}
