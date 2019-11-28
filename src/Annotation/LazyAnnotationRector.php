<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Annotation;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

final class LazyAnnotationRector extends AbstractRector
{
    /**
     * @var string
     */
    private $oldAnnotation = 'lazy';

    /**
     * @var string
     */
    private $newAnnotation = 'TYPO3\CMS\Extbase\Annotation\ORM\Lazy';

    public function getNodeTypes(): array
    {
        return [Property::class];
    }

    /**
     * Process Node of matched type.
     *
     * @param Node $node
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->docBlockManipulator->hasTag($node, $this->oldAnnotation)) {
            return null;
        }

        $this->docBlockManipulator->replaceAnnotationInNode($node, $this->oldAnnotation, $this->newAnnotation);

        return $node;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Turns properties with `@annotation` to properties with `@newAnnotation`',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
/**
 * @lazy
 */
private $someProperty;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
/**
 * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
 */
private $someProperty;

CODE_SAMPLE
                ),
            ]
        );
    }
}
