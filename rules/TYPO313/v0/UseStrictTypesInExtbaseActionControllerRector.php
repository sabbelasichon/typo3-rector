<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102632-UseStrictTypesInExtbase.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\UseStrictTypesInExtbaseActionControllerRector\UseStrictTypesInExtbaseActionControllerRectorTest
 */
final class UseStrictTypesInExtbaseActionControllerRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use strict types in Extbase ActionController', [new CodeSample(
            <<<'CODE_SAMPLE'
namespace Vendor\MyExtension\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public $defaultViewObjectName = JsonView::class;
    public $errorMethodName = 'myAction';
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
namespace Vendor\MyExtension\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public string $defaultViewObjectName = JsonView::class;
    public string $errorMethodName = 'myAction';
}
CODE_SAMPLE
        )]);
    }

    public function getNodeTypes(): array
    {
        return [];
    }

    public function refactor(Node $node)
    {
        return null;
    }
}
