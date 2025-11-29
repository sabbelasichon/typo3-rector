<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-108227-UsageOfIgnoreValidationAndValidateAttributesForParametersAtMethodLevel.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\UsageOfValidationAttributesAtMethodLevelRector\UsageOfValidationAttributesAtMethodLevelRectorTest
 */
final class UsageOfValidationAttributesAtMethodLevelRector extends AbstractRector implements DocumentedRuleInterface, MinPhpVersionInterface
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
        return new RuleDefinition(
            'Usage of `#[IgnoreValidation]` and `#[Validate]` attributes for parameters at method level',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Attribute\IgnoreValidation;
use TYPO3\CMS\Extbase\Attribute\Validate;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

final class MyController extends ActionController
{
    #[IgnoreValidation(argumentName: 'something')]
    public function barAction(string $something): ResponseInterface
    {
    }

    #[Validate(validator: 'NotEmpty', param: 'anythingNotEmpty')]
    public function bazAction(string $anythingNotEmpty): ResponseInterface
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Attribute\IgnoreValidation;
use TYPO3\CMS\Extbase\Attribute\Validate;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

final class MyController extends ActionController
{
    public function barAction(
        #[IgnoreValidation]
        string $something
    ): ResponseInterface {
    }

    public function bazAction(
        #[Validate(validator: 'NotEmpty')]
        string $anythingNotEmpty
    ): ResponseInterface {
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->attrGroups === []) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->attrGroups as $groupKey => $attributeGroup) {
            foreach ($attributeGroup->attrs as $attrKey => $attribute) {
                if ($this->isName($attribute->name, 'TYPO3\CMS\Extbase\Attribute\IgnoreValidation')) {
                    if ($this->refactorIgnoreValidation($node, $attributeGroup, $attrKey, $attribute)) {
                        $hasChanged = true;
                    }

                    continue;
                }

                if ($this->isName($attribute->name, 'TYPO3\CMS\Extbase\Attribute\Validate')
                    && $this->refactorValidate($node, $attributeGroup, $attrKey, $attribute)
                ) {
                    $hasChanged = true;
                }
            }

            // Remove empty attribute groups
            if ($attributeGroup->attrs === []) {
                unset($node->attrGroups[$groupKey]);
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ATTRIBUTES;
    }

    private function refactorIgnoreValidation(
        ClassMethod $classMethod,
        AttributeGroup $attributeGroup,
        int $attrKey,
        Attribute $attribute
    ): bool {
        $argumentNameArg = $this->findArg($attribute, 'argumentName');
        if (! $argumentNameArg instanceof Arg) {
            return false;
        }

        $paramName = $this->valueResolver->getValue($argumentNameArg->value);
        if (! is_string($paramName)) {
            return false;
        }

        $param = $this->findParamByName($classMethod, $paramName);
        if (! $param instanceof Param) {
            return false;
        }

        // Create new attribute without the argumentName argument
        $newArgs = [];
        foreach ($attribute->args as $arg) {
            if ($this->isName($arg->name, 'argumentName')) {
                continue;
            }

            $newArgs[] = $arg;
        }

        $param->attrGroups[] = new AttributeGroup([new Attribute($attribute->name, $newArgs)]);

        // Remove the attribute from the method
        unset($attributeGroup->attrs[$attrKey]);

        return true;
    }

    private function refactorValidate(
        ClassMethod $classMethod,
        AttributeGroup $attributeGroup,
        int $attrKey,
        Attribute $attribute
    ): bool {
        $paramArg = $this->findArg($attribute, 'param');
        if (! $paramArg instanceof Arg) {
            return false;
        }

        $paramName = $this->valueResolver->getValue($paramArg->value);
        if (! is_string($paramName)) {
            return false;
        }

        $param = $this->findParamByName($classMethod, $paramName);
        if (! $param instanceof Param) {
            return false;
        }

        // Create new attribute without the param argument
        $newArgs = [];
        foreach ($attribute->args as $arg) {
            if ($this->isName($arg->name, 'param')) {
                continue;
            }

            $newArgs[] = $arg;
        }

        $param->attrGroups[] = new AttributeGroup([new Attribute($attribute->name, $newArgs)]);

        // Remove the attribute from the method
        unset($attributeGroup->attrs[$attrKey]);

        return true;
    }

    private function findArg(Attribute $attribute, string $name): ?Arg
    {
        foreach ($attribute->args as $arg) {
            if ($arg->name instanceof Identifier && $this->isName($arg->name, $name)) {
                return $arg;
            }
        }

        return null;
    }

    private function findParamByName(ClassMethod $classMethod, string $name): ?Param
    {
        foreach ($classMethod->params as $param) {
            if ($this->isName($param->var, $name)) {
                return $param;
            }
        }

        return null;
    }
}
