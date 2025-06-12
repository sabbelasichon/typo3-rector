<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.3/Deprecation-104304-BackendUtilitygetTcaFieldConfiguration.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateBackendUtilityGetTcaFieldConfigurationRector\MigrateBackendUtilityGetTcaFieldConfigurationRectorTest
 */
final class MigrateBackendUtilityGetTcaFieldConfigurationRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `BackendUtility::getTcaFieldConfiguration()`', [new CodeSample(
            <<<'CODE_SAMPLE'
$fieldConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getTcaFieldConfiguration('my_table', 'my_field');
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$fieldConfig = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Schema\TcaSchemaFactory::class)->get('my_table')->getField('my_field')->getConfiguration();
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $makeInstanceCall = $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Core\Utility\GeneralUtility',
            'makeInstance',
            [$this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Schema\TcaSchemaFactory')]
        );

        return new MethodCall(
            new MethodCall(
                new MethodCall($makeInstanceCall, 'get', [$node->args[0]]),
                'getField',
                [$node->args[1]]
            ),
            'getConfiguration'
        );
    }

    private function shouldSkip(StaticCall $staticCall): bool
    {
        if (! $this->isObjectType($staticCall->class, new ObjectType('TYPO3\CMS\Backend\Utility\BackendUtility'))) {
            return true;
        }

        if (! $this->isName($staticCall->name, 'getTcaFieldConfiguration')) {
            return true;
        }

        return count($staticCall->args) !== 2;
    }
}
