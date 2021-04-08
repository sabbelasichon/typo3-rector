<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v5;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.5/Deprecation-85981-AnnotationFlushesCaches.html
 */
final class RemoveFlushCachesRector extends AbstractRector
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
     * @return array<class-string<Node>>
=======
     * @return array<class-string<\PhpParser\Node>>
>>>>>>> f7cbd4b... make PHPStan smarted on getNodeTypes()
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
        /** @var string $name */
        $name = $this->getName($node);
        if (! Strings::endsWith($name, 'Command')) {
            return null;
        }
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $node->getAttribute(PhpDocInfo::class);
        if (null === $phpDocInfo) {
            return null;
        }
        $this->phpDocTagRemover->removeByName($phpDocInfo, 'flushCaches');
        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove @flushesCaches annotation', [new CodeSample(<<<'CODE_SAMPLE'
/**
 * My command
 *
 * @flushesCaches
 */
public function myCommand()
{
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
/**
 * My Command
 */
public function myCommand()
{
}

CODE_SAMPLE
)]);
    }
}
