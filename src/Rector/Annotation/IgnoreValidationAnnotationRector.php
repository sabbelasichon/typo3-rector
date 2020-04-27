<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Annotation;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Feature-83094-ReplaceIgnorevalidationWithTYPO3CMSExtbaseAnnotationIgnoreValidation.html
 */
final class IgnoreValidationAnnotationRector extends AbstractRector
{
    /**
     * @var string
     */
    private $oldAnnotation = 'ignorevalidation';

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $node->getAttribute(AttributeKey::PHP_DOC_INFO);
        if (null === $phpDocInfo) {
            return null;
        }

        if (! $phpDocInfo->hasByName($this->oldAnnotation)) {
            return null;
        }

        $tagNode = $phpDocInfo->getTagsByName($this->oldAnnotation)[0];

        $tagName = '@TYPO3\CMS\Extbase\Annotation\IgnoreValidation("' . ltrim((string) $tagNode->value, '$') . '")';
        $phpDocInfo->addBareTag($tagName);

        $phpDocInfo->removeByName($this->oldAnnotation);

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
