<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Annotation;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Feature-83078-ReplaceLazyWithTYPO3CMSExtbaseAnnotationORMLazy.html
 */
final class LazyAnnotationRector extends AbstractRector
{
    /**
     * @var string
     */
    private const OLD_ANNOTATION = 'lazy';

    /**
     * @var string
     */
    private const NEW_ANNOTATION = 'TYPO3\CMS\Extbase\Annotation\ORM\Lazy';

    /**
     * @return string[]
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
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $node->getAttribute(AttributeKey::PHP_DOC_INFO);
        if (null === $phpDocInfo) {
            return null;
        }

        if (! $phpDocInfo->hasByName(self::OLD_ANNOTATION)) {
            return null;
        }

        $this->docBlockManipulator->replaceAnnotationInNode($node, self::OLD_ANNOTATION, self::NEW_ANNOTATION);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Turns properties with `@lazy` to properties with `@TYPO3\CMS\Extbase\Annotation\ORM\Lazy`',
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
