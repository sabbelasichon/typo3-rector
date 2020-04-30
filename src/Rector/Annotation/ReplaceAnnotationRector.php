<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Annotation;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\ConfiguredCodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Feature-83092-ReplaceTransientWithTYPO3CMSExtbaseAnnotationORMTransient.html
 */
final class ReplaceAnnotationRector extends AbstractRector
{
    /**
     * @var string[]
     */
    private $oldToNewAnnotations = [];

    /**
     * @param string[] $oldToNewAnnotations
     */
    public function __construct(array $oldToNewAnnotations = [])
    {
        $this->oldToNewAnnotations = $oldToNewAnnotations;
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
        $phpDocInfo = $node->getAttribute(AttributeKey::PHP_DOC_INFO);
        if (null === $phpDocInfo) {
            return null;
        }

        foreach ($this->oldToNewAnnotations as $oldAnnotation => $newAnnotation) {
            if (! $phpDocInfo->hasByName($oldAnnotation)) {
                continue;
            }

            $phpDocInfo->removeByName($oldAnnotation);
            $phpDocInfo->addBareTag($newAnnotation);
        }

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Replace old annotation by new one',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
/**
 * @transient
 */
private $someProperty;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
/**
 * @TYPO3\CMS\Extbase\Annotation\ORM\Transient
 */
private $someProperty;

CODE_SAMPLE
                , [
                    '$oldToNewAnnotations' => [
                        'transient' => 'TYPO3\CMS\Extbase\Annotation\ORM\Transient',
                    ],
                ]),
            ]
        );
    }
}
