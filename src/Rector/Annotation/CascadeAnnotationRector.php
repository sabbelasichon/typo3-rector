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

        if (!$phpDocInfo->hasByName($this->oldAnnotation)) {
            return null;
        }

        $phpDocInfo->removeByName($this->oldAnnotation);
        $phpDocInfo->addBareTag($this->newAnnotation);

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
