<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typo3;

use PhpParser\Builder\Property;
use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/12.0/Deprecation-97435-UsageOfSiteLanguageAwareTraitToDenoteSiteLanguageAwareness.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ImplementSiteLanguageAwareInterfaceRector\ImplementSiteLanguageAwareInterfaceRectorTest
 */
final class ImplementSiteLanguageAwareInterfaceRector extends AbstractScopeAwareRector
{
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
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        $classHasChanged = false;

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        foreach ($node->getTraitUses() as $traitUse) {
            foreach ($traitUse->traits as $trait) {
                if (! $this->isName($trait, 'TYPO3\CMS\Core\Site\SiteLanguageAwareTrait')) {
                    continue;
                }

                $this->removeNode($traitUse);
                $classHasChanged = true;
            }
        }

        // It was not working with the ClassReflection
        $implementInterface = true;
        foreach ($node->implements as $implement) {
            if ($this->isName($implement, 'TYPO3\\CMS\\Core\\Site\\SiteLanguageAwareInterface')) {
                $implementInterface = false;
            }
        }

        if ($implementInterface) {
            $node->implements[] = new FullyQualified('TYPO3\CMS\Core\Site\SiteLanguageAwareInterface');
            $classHasChanged = true;
        }

        $siteLanguageName = new FullyQualified('TYPO3\CMS\Core\Site\Entity\SiteLanguage');

        $hasSiteLanguageProperty = false;
        $properties = $node->getProperties();
        foreach ($properties as $property) {
            if ($this->isName($property, 'siteLanguage')) {
                $hasSiteLanguageProperty = true;
            }
        }

        if (! $hasSiteLanguageProperty) {
            $node->stmts[] = (new Property('siteLanguage'))
                ->makeProtected()
                ->setType($siteLanguageName)
                ->getNode();
            $classHasChanged = true;
        }

        if (! $classReflection->hasMethod('setSiteLanguage')) {
            $setterOfSiteLanguage = new ClassMethod(
                'setSiteLanguage',
                [
                    'flags' => Class_::MODIFIER_PUBLIC,
                    'stmts' => [
                        new Node\Stmt\Expression(
                            $this->nodeFactory->createPropertyAssignmentWithExpr(
                                'siteLanguage',
                                new Node\Expr\Variable('siteLanguage')
                            )
                        ),
                    ],
                    'params' => [new Node\Param(new Node\Expr\Variable('siteLanguage'), null, $siteLanguageName)],
                ]
            );
            $node->stmts[] = $setterOfSiteLanguage;
            $classHasChanged = true;
        }

        if (! $classReflection->hasMethod('getSiteLanguage')) {
            $getterOfSiteLanguage = new ClassMethod(
                'getSiteLanguage',
                [
                    'flags' => Class_::MODIFIER_PUBLIC,
                    'stmts' => [new Return_($this->nodeFactory->createPropertyFetch('this', 'siteLanguage'))],
                    'returnType' => $siteLanguageName,
                ]
            );
            $node->stmts[] = $getterOfSiteLanguage;
            $classHasChanged = true;
        }

        return $classHasChanged ? $node : null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Implement SiteLanguageAwareInterface instead of using SiteLanguageAwareTrait', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Site\SiteLanguageAwareTrait;

class MyClass
{
    use SiteLanguageAwareTrait;
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Site\SiteLanguageAwareInterface;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

class MyClass implements SiteLanguageAwareInterface
{

    protected SiteLanguage $siteLanguage;

    public function setSiteLanguage(SiteLanguage $siteLanguage)
    {
        $this->siteLanguage = $siteLanguage;
    }

    public function getSiteLanguage(): SiteLanguage
    {
        return $this->siteLanguage;
    }
}
CODE_SAMPLE
            ),
        ]);
    }
}
