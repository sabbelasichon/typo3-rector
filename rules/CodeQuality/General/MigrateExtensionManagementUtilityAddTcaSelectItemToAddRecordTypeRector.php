<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Contract\NoChangelogRequiredInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MigrateExtensionManagementUtilityAddTcaSelectItemToAddRecordTypeRector\MigrateExtensionManagementUtilityAddTcaSelectItemToAddRecordTypeRectorTest
 */
final class MigrateExtensionManagementUtilityAddTcaSelectItemToAddRecordTypeRector extends AbstractRector implements DocumentedRuleInterface, NoChangelogRequiredInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate `ExtensionManagementUtility::addTcaSelectItem()` for tt_content.CType to `ExtensionManagementUtility::addRecordType()`',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => 'My Content Element',
        'value' => 'my_content_element',
        'icon' => 'my-icon-identifier',
        'group' => 'group1',
        'description' => 'My Description',
    ]
);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addRecordType(
    [
        'label' => 'My Content Element',
        'value' => 'my_content_element',
        'icon' => 'my-icon-identifier',
        'group' => 'group1',
        'description' => 'My Description',
    ],
    ''
);
CODE_SAMPLE
                ),
            ]
        );
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
        if (! $this->isName($node->class, 'TYPO3\CMS\Core\Utility\ExtensionManagementUtility')) {
            return null;
        }

        if (! $this->isName($node->name, 'addTcaSelectItem')) {
            return null;
        }

        $args = $node->getArgs();
        if (count($args) < 3) {
            return null;
        }

        if (count($args) > 3) {
            return null;
        }

        $table = $args[0]->value;
        if (! $table instanceof String_ || $table->value !== 'tt_content') {
            return null;
        }

        if (! $this->isCTypeField($args[1]->value)) {
            return null;
        }

        $node->name = new Identifier('addRecordType');
        $showItemListArg = new Arg(new String_(''));
        $showItemListArg->setAttribute(AttributeKey::COMMENTS, [
            new Comment('// TODO: Important! Add showItemList yourself'),
        ]);
        $node->args = [$args[2], $showItemListArg];

        return $node;
    }

    private function isCTypeField(Expr $expr): bool
    {
        if ($expr instanceof String_) {
            return $expr->value === 'CType';
        }

        if ($expr instanceof ClassConstFetch) {
            return $this->isName($expr->class, 'TYPO3\CMS\Extbase\Utility\ExtensionUtility')
                && $this->isName($expr->name, 'PLUGIN_TYPE_CONTENT_ELEMENT');
        }

        return false;
    }
}
