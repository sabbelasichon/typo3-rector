<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v4;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.4.x/Important-105653-RequireATemplateFilenameInExtbaseModuleTemplateRendering.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v4\RequireATemplateFileNameInExtbaseModuleTemplateRenderingRector\RequireATemplateFileNameInExtbaseModuleTemplateRenderingRectorTest
 */
final class RequireATemplateFileNameInExtbaseModuleTemplateRenderingRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Require a template filename in extbase module template rendering', [new CodeSample(
            <<<'CODE_SAMPLE'
$moduleTemplate->renderResponse();
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$moduleTemplate->renderResponse('MyController/MyAction');
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $scope = ScopeFetcher::fetch($node);

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        $className = $this->getShortClassName($classReflection->getName());
        $shortClassName = preg_replace('/Controller$/', '', $className);

        $functionName = $scope->getFunctionName() ?? '';
        $templateActionName = preg_replace('/Action$/', '', ucfirst($functionName));

        $templateName = $shortClassName . '/' . $templateActionName;
        $node->args = [new Arg(new String_($templateName))];

        return $node;
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if (! $this->isObjectType($methodCall->var, new ObjectType('TYPO3\CMS\Backend\Template\ModuleTemplate'))) {
            return true;
        }

        if (! $this->isName($methodCall->name, 'renderResponse')) {
            return true;
        }

        return $methodCall->args !== [];
    }

    private function getShortClassName(string $className): string
    {
        $lastSlashPosition = strrpos($className, '\\');
        if ($lastSlashPosition !== false) {
            $className = substr($className, $lastSlashPosition + 1);
        }

        return $className;
    }
}
