<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Annotation;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Deprecation-83093-ReplaceCascadeWithTYPO3CMSExtbaseAnnotationORMCascade.html
 */
final class CascadeAnnotationRector extends AbstractRector
{
    /**
     * @var string
     */
    private $oldAnnotation = 'cascade';

    /**
     * @var string
     */
    private $newAnnotation = '@TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")';

    public function getNodeTypes(): array
    {
        return [Property::class];
    }

    /**
     * Process Node of matched type.
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->docBlockManipulator->hasTag($node, $this->oldAnnotation)) {
            return null;
        }

        $this->docBlockManipulator->removeTagFromNode($node, $this->oldAnnotation);
        $this->docBlockManipulator->addTag($node, new PhpDocTagNode($this->newAnnotation, new GenericTagValueNode('')));

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Turns properties with `@cascade` to properties with `@TYPO3\CMS\Extbase\Annotation\ORM\Cascade`',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
/**
 * @cascade
 */
private $someProperty;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
/**
 * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
 */
private $someProperty;

CODE_SAMPLE
                ),
            ]
        );
    }
}
