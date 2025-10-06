<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-107578-PrepareExtadminpanelDataProviderInterfaceChange.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateAdminPanelDataProviderInterfaceRector\MigrateAdminPanelDataProviderInterfaceRectorTest
 */
final class MigrateAdminPanelDataProviderInterfaceRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate Adminpanel DataProviderInterface', [new CodeSample(
            <<<'CODE_SAMPLE'
public function getDataToStore(\Psr\Http\Message\ServerRequestInterface $request): ModuleData;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
public function getDataToStore(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response): ModuleData;
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $node->params[] = new Param(
            new Variable('response'),
            null,
            new FullyQualified('Psr\Http\Message\ResponseInterface')
        );

        return $node;
    }

    private function shouldSkip(ClassMethod $classMethod): bool
    {
        if (! $this->implementsDataProviderInterface($classMethod)) {
            return true;
        }

        if (! $this->isName($classMethod->name, 'getDataToStore')) {
            return true;
        }

        return count($classMethod->params) === 2;
    }

    private function implementsDataProviderInterface(ClassMethod $node): bool
    {
        $scope = ScopeFetcher::fetch($node);

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        return $classReflection->implementsInterface('TYPO3\CMS\Adminpanel\ModuleApi\DataProviderInterface');
    }
}
