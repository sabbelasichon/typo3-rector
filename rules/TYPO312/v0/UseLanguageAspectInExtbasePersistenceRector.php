<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-97926-ExtbaseQuerySettingsMethodsRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\UseLanguageAspectInExtbasePersistenceRector\UseLanguageAspectInExtbasePersistenceRectorTest
 */
final class UseLanguageAspectInExtbasePersistenceRector extends AbstractRector
{
    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use LanguageAspect in Extbase Persistence', [new CodeSample(
            <<<'CODE_SAMPLE'
$query = $this->createQuery();
$query->getQuerySettings()->setLanguageOverlayMode(false);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Context\LanguageAspect;

$query = $this->createQuery();
$languageAspect = $query->getQuerySettings()->getLanguageAspect();
$languageAspect = new LanguageAspect($languageAspect->getId(), $languageAspect->getContentId(), LanguageAspect::OVERLAYS_OFF);
$query->getQuerySettings()->setLanguageAspect($languageAspect);
CODE_SAMPLE
        ), new CodeSample(
            <<<'CODE_SAMPLE'
$query = $this->createQuery();
$query->getQuerySettings()->setLanguageOverlayMode(true);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Context\LanguageAspect;

$query = $this->createQuery();
$languageAspect = $query->getQuerySettings()->getLanguageAspect();
$languageAspect = new LanguageAspect($languageAspect->getId(), $languageAspect->getContentId(), LanguageAspect::OVERLAYS_MIXED);
$query->getQuerySettings()->setLanguageAspect($languageAspect);
CODE_SAMPLE
        ), new CodeSample(
            <<<'CODE_SAMPLE'
$query = $this->createQuery();
$query->getQuerySettings()->setLanguageOverlayMode('hideNonTranslated');
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Context\LanguageAspect;

$query = $this->createQuery();
$languageAspect = $query->getQuerySettings()->getLanguageAspect();
$languageAspect = new LanguageAspect($languageAspect->getId(), $languageAspect->getContentId(), LanguageAspect::OVERLAYS_ON);
$query->getQuerySettings()->setLanguageAspect($languageAspect);
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     * @return list<Expression>
     */
    public function refactor(Node $node): ?array
    {
        if (! $node->expr instanceof MethodCall) {
            return null;
        }

        $methodCall = $node->expr;
        if (! $this->isName($methodCall->name, 'setLanguageOverlayMode')) {
            return null;
        }

        $querySettingsCall = $methodCall->var;
        if (! $this->isObjectType(
            $querySettingsCall,
            new ObjectType('TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface')
        )) {
            return null;
        }

        $args = $methodCall->args;
        if (count($args) !== 1) {
            return null;
        }

        $argValue = $args[0]->value;

        // Determine the correct LanguageAspect constant based on the argument value
        $languageModeConstant = 'OVERLAYS_OFF';
        if ($argValue instanceof String_ && $argValue->value === 'hideNonTranslated') {
            $languageModeConstant = 'OVERLAYS_ON';
        } elseif ($this->valueResolver->isTrue($argValue) || $this->valueResolver->isValue($argValue, '1')) {
            $languageModeConstant = 'OVERLAYS_MIXED';
        }

        $languageAspectVar = new Variable('languageAspect');

        // 1. $languageAspect = $query->getQuerySettings()->getLanguageAspect();
        $getLanguageAspectCall = $this->nodeFactory->createMethodCall($querySettingsCall, 'getLanguageAspect');
        $statement1 = new Expression(new Assign($languageAspectVar, $getLanguageAspectCall));

        // 2. $languageAspect = new LanguageAspect($languageAspect->getId(), $languageAspect->getContentId(), <constant>);
        $newLanguageAspect = new New_(
            new FullyQualified('TYPO3\CMS\Core\Context\LanguageAspect'),
            [
                $this->nodeFactory->createArg($this->nodeFactory->createMethodCall($languageAspectVar, 'getId')),
                $this->nodeFactory->createArg($this->nodeFactory->createMethodCall($languageAspectVar, 'getContentId')),
                $this->nodeFactory->createArg(
                    $this->nodeFactory->createClassConstFetch(
                        'TYPO3\CMS\Core\Context\LanguageAspect',
                        $languageModeConstant
                    )
                ),
            ]
        );
        $statement2 = new Expression(new Assign($languageAspectVar, $newLanguageAspect));

        // 3. $query->getQuerySettings()->setLanguageAspect($languageAspect);
        $statement3 = new Expression(
            $this->nodeFactory->createMethodCall(
                $querySettingsCall,
                'setLanguageAspect',
                [$this->nodeFactory->createArg($languageAspectVar)]
            )
        );

        return [$statement1, $statement2, $statement3];
    }
}
