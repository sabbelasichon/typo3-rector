<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v0;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Rector\AbstractRector;
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

    public function __construct(InjectMethodFactory $injectMethodFactory)
    {
        $this->injectMethodFactory = $injectMethodFactory;
    }

    /**
     * @return array<class-string<Node>>
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
        $injectMethods = [];
        $properties = $node->getProperties();
        foreach ($properties as $property) {
            $propertyPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);
            if (! $propertyPhpDocInfo->hasByAnnotationClass(self::OLD_ANNOTATION)) {
                continue;
            }

            $injectMethods = array_merge(
                $injectMethods,
                $this->injectMethodFactory->createInjectMethodStatements($node, $property, self::OLD_ANNOTATION)
            );
        }

        $node->stmts = array_merge($node->stmts, $injectMethods);
        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
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
}
