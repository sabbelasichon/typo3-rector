<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v0;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Nop;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\InjectMethodFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Breaking-90799-DependencyInjectionWithNonPublicPropertiesHasBeenRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v0\ReplaceInjectAnnotationWithMethodRector\ReplaceInjectAnnotationWithMethodRectorTest
 */
final class ReplaceInjectAnnotationWithMethodRector extends AbstractRector
{
    /**
     * @var class-string
     */
    private const OLD_ANNOTATION = 'TYPO3\CMS\Extbase\Annotation\Inject';

    /**
     * @readonly
     */
    private InjectMethodFactory $injectMethodFactory;

    /**
     * @readonly
     */
    private PhpDocInfoFactory $phpDocInfoFactory;

    public function __construct(InjectMethodFactory $injectMethodFactory, PhpDocInfoFactory $phpDocInfoFactory)
    {
        $this->injectMethodFactory = $injectMethodFactory;
        $this->phpDocInfoFactory = $phpDocInfoFactory;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Turns properties with `@TYPO3\CMS\Extbase\Annotation\Inject` to setter injection', [
            new CodeSample(
                <<<'CODE_SAMPLE'
/**
 * @var SomeService
 * @TYPO3\CMS\Extbase\Annotation\Inject
 */
private $someService;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
/**
 * @var SomeService
 */
private $someService;

public function injectSomeService(SomeService $someService)
{
    $this->someService = $someService;
}

CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $injectMethods = [];
        foreach ($node->getProperties() as $property) {
            $propertyPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);
            if (! $propertyPhpDocInfo->hasByAnnotationClass(self::OLD_ANNOTATION)) {
                continue;
            }

            $statements = $this->injectMethodFactory->createInjectMethodStatements(
                $node,
                $property,
                self::OLD_ANNOTATION
            );
            foreach ($statements as $statement) {
                $injectMethods[] = $statement;
            }
        }

        if ($injectMethods === []) {
            return null;
        }

        $injectMethods = $this->collapseConsecutiveNops($injectMethods);

        $node->stmts = array_merge($node->stmts, $injectMethods);

        return $node;
    }

    /**
     * Removes consecutive Nop statements from a list of nodes.
     *
     * @param Node\Stmt[] $nodes
     * @return Node\Stmt[]
     */
    private function collapseConsecutiveNops(array $nodes): array
    {
        $result = [];
        $prevWasNop = false;

        foreach ($nodes as $node) {
            if ($node instanceof Nop) {
                if ($prevWasNop) {
                    continue;
                }

                $prevWasNop = true;
            } else {
                $prevWasNop = false;
            }

            $result[] = $node;
        }

        return $result;
    }
}
