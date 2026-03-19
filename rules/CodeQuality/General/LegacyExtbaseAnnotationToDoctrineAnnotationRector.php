<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.0/Feature-82869-ReplaceInjectWithTYPO3CMSExtbaseAnnotationInject.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\LegacyExtbaseAnnotationToDoctrineAnnotationRector\LegacyExtbaseAnnotationToDoctrineAnnotationRectorTest
 */
final class LegacyExtbaseAnnotationToDoctrineAnnotationRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace legacy extbase annotations with doctrine annotations', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class MyClass
{
    /**
     * @var SomeService
     * @inject
     */
    private $someService;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     * @lazy
     */
    protected $items;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     * @cascade remove
     */
    protected $images;

    /**
     * @validate NotEmpty
     */
    protected $title;

    /**
     * @ignorevalidation $param
     */
    public function myAction($param) {}
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class MyClass
{
    /**
     * @var SomeService
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    private $someService;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $items;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $images;

    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $title;

    /**
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("param")
     */
    public function myAction($param) {}
}
CODE_SAMPLE
            ),
        ]);
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
        $docComment = $node->getDocComment();
        if (! $docComment instanceof Doc) {
            return null;
        }

        $text = $docComment->getText();
        $newText = $text;

        // @inject → @TYPO3\CMS\Extbase\Annotation\Inject
        $newText = preg_replace('/@inject\b/', '@TYPO3\CMS\Extbase\Annotation\Inject', $newText);

        // @lazy → @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
        $newText = preg_replace('/@lazy\b/', '@TYPO3\CMS\Extbase\Annotation\ORM\Lazy', $newText);

        // @cascade remove → @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
        $newText = preg_replace('/@cascade\s+(\w+)/', '@TYPO3\CMS\Extbase\Annotation\ORM\Cascade("$1")', $newText);

        // @validate ValidatorName → @TYPO3\CMS\Extbase\Annotation\Validate("ValidatorName")
        $newText = preg_replace('/@validate\s+(\w+)/', '@TYPO3\CMS\Extbase\Annotation\Validate("$1")', $newText);

        // @ignorevalidation $param → @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("param")
        $newText = preg_replace(
            '/@ignorevalidation\s+\$(\w+)/',
            '@TYPO3\CMS\Extbase\Annotation\IgnoreValidation("$1")',
            $newText
        );

        if ($newText === $text) {
            return null;
        }

        $node->setDocComment(new Doc($newText));

        return $node;
    }
}
