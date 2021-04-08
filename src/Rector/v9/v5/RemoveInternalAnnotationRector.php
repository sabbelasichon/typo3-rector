<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v5;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.5/Deprecation-85980-InternalAnnotationInExtbaseCommands.html
 */
final class RemoveInternalAnnotationRector extends AbstractRector
{
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
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isObjectType($node, CommandController::class)) {
            return null;
        }
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        if (null === $phpDocInfo) {
            return null;
        }
        $this->phpDocTagRemover->removeByName($phpDocInfo, 'internal');
        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove @internal annotation from classes extending \TYPO3\CMS\Extbase\Mvc\Controller\CommandController',
            [
                new CodeSample(<<<'CODE_SAMPLE'
/**
 * @internal
 */
class MyCommandController extends CommandController
{
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class MyCommandController extends CommandController
{
}
CODE_SAMPLE
),
            ]
        );
    }
}
