<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Feature-83092-ReplaceTransientWithTYPO3CMSExtbaseAnnotationORMTransient.html
 */
final class ReplaceAnnotationRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const OLD_TO_NEW_ANNOTATIONS = 'old_to_new_annotations';

    /**
     * @var array<string, string>
     */
    private $oldToNewAnnotations = [];

    /**
     * @var PhpDocTagRemover
     */
    private $phpDocTagRemover;

    public function __construct(PhpDocTagRemover $phpDocTagRemover)
    {
        $this->phpDocTagRemover = $phpDocTagRemover;
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
     * @return array<class-string<Node>>
=======
=======
>>>>>>> da7142f... rename AbstractCommunityRectorTestCase to AbstractRectorTestCase
     * @return array<class-string<\PhpParser\Node>>
>>>>>>> f7cbd4b... make PHPStan smarted on getNodeTypes()
     */

    /**
=======
>>>>>>> 8781ff4... rename AbstractCommunityRectorTestCase to AbstractRectorTestCase
=======
>>>>>>> cd548b8... use ObjectType wrapper
     * @return array<class-string<\PhpParser\Node>>
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
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        if (null === $phpDocInfo) {
            return null;
        }
        foreach ($this->oldToNewAnnotations as $oldAnnotation => $newAnnotation) {
            if (! $phpDocInfo->hasByName($oldAnnotation)) {
                continue;
            }
            $this->phpDocTagRemover->removeByName($phpDocInfo, $oldAnnotation);

            $tag = '@' . ltrim($newAnnotation, '@');

            $phpDocTagNode = new PhpDocTagNode($tag, new GenericTagValueNode(''));
            $phpDocInfo->addPhpDocTagNode($phpDocTagNode);
        }
        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace old annotation by new one', [new ConfiguredCodeSample(<<<'CODE_SAMPLE'
/**
 * @transient
 */
private $someProperty;
CODE_SAMPLE
, <<<'CODE_SAMPLE'
/**
 * @TYPO3\CMS\Extbase\Annotation\ORM\Transient
 */
private $someProperty;

CODE_SAMPLE
, [
    self::OLD_TO_NEW_ANNOTATIONS => [
        'transient' => 'TYPO3\CMS\Extbase\Annotation\ORM\Transient',
    ],
])]);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $this->oldToNewAnnotations = $configuration[self::OLD_TO_NEW_ANNOTATIONS] ?? [];
    }
}
