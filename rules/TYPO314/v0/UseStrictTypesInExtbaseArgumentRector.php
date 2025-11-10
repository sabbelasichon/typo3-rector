<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-107777-UseStrictTypesInExtbaseArgument.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseStrictTypesInExtbaseArgumentRector\UseStrictTypesInExtbaseArgumentRectorTest
 */
final class UseStrictTypesInExtbaseArgumentRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use strict types in Extbase Argument', [new CodeSample(
            <<<'CODE_SAMPLE'
namespace Vendor\MyExtension\Controller\Argument;

use TYPO3\CMS\Extbase\Mvc\Controller\Argument;

class MyArgument extends Argument
{
    protected $propertyMappingConfiguration;
    protected $name = '';
    protected $shortName;
    protected $dataType;
    protected $isRequired = false;
    protected $value;
    protected $defaultValue;
    protected $validator;
    protected $validationResults;

    public function __construct($name, $dataType) {}
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
namespace Vendor\MyExtension\Controller\Argument;

use TYPO3\CMS\Extbase\Mvc\Controller\Argument;

class MyArgument extends Argument
{
    protected MvcPropertyMappingConfiguration $propertyMappingConfiguration';
    protected string $name = '';
    protected string $shortName = '';
    protected string $dataType = '';
    protected bool $isRequired = false;
    protected mixed $value = null;
    protected mixed $defaultValue = null;
    protected ?ValidatorInterface $validator = null;
    protected Result $validationResults;

    public function __construct(string $name, string $dataType) {}
}
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [];
    }

    public function refactor(Node $node): ?Node
    {
        return null;
    }
}
