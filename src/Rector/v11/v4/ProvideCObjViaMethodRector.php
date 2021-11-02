<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\Astral\ValueObject\NodeBuilder\MethodBuilder;
use Symplify\Astral\ValueObject\NodeBuilder\ParamBuilder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/11.4/Deprecation-94956-PublicCObj.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v4\ProvideCObjViaMethodRector\ProvideCObjViaMethodRectorTest
 */
final class ProvideCObjViaMethodRector extends AbstractRector
{
    /**
     * @var string
     */
    private const COBJ = 'cObj';

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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $cObjProperty = $node->getProperty(self::COBJ);

        if (! $cObjProperty instanceof Property) {
            return null;
        }

        if (! $cObjProperty->isPublic()) {
            return null;
        }

        $this->visibilityManipulator->makeProtected($cObjProperty);

        $this->addSetContentObjectRendererMethod($node);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replaces public $cObj with protected and set via method', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class Foo
{
    public $cObj;
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class Foo
{
    protected $cObj;

    public function setContentObjectRenderer(ContentObjectRenderer $cObj): void
    {
        $this->cObj = $cObj;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    private function addSetContentObjectRendererMethod(Class_ $node): void
    {
        $paramBuilder = new ParamBuilder(self::COBJ);
        $paramBuilder->setType(new FullyQualified('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer'));

        $param = $paramBuilder->getNode();
        $propertyAssignNode = $this->nodeFactory->createPropertyAssignmentWithExpr(
            self::COBJ,
            new Variable(self::COBJ)
        );

        $classMethodBuilder = new MethodBuilder('setContentObjectRenderer');
        $classMethodBuilder->addParam($param);
        $classMethodBuilder->addStmt($propertyAssignNode);
        $classMethodBuilder->makePublic();
        $classMethodBuilder->setReturnType('void');
        $node->stmts[] = new Nop();
        $node->stmts[] = $classMethodBuilder->getNode();
    }

    private function shouldSkip(Class_ $node): bool
    {
        if ($this->isObjectType($node, new ObjectType('TYPO3\CMS\Frontend\Plugin\AbstractPlugin'))) {
            return true;
        }

        if ($this->isObjectType($node, new ObjectType('TYPO3\CMS\Extbase\Mvc\Controller\ActionController'))) {
            return true;
        }

        $classMethod = $node->getMethod('setContentObjectRenderer');
        if ($classMethod instanceof ClassMethod) {
            return true;
        }

        return false;
    }
}
