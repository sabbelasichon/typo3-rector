<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v3;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use Rector\Php\PhpVersionProvider;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.3/Feature-99739-AssociativeArrayKeysForTCAItems.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v3\UseSelectItemInsteadOfArrayRector\UseSelectItemInsteadOfArrayRectorTest
 */
final class UseSelectItemInsteadOfArrayRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var string
     */
    private const SELECT_ITEM_TYPE = 'select';

    /**
     * @var array<int, string>
     */
    private const LEGACY_INDEXED_KEYS = [
        0 => 'label',
        1 => 'value',
        2 => 'icon',
        3 => 'group',
        4 => 'description',
    ];

    /**
     * @var string[]
     */
    private const SELECT_ITEM_KEYS = ['type', 'label', 'value', 'icon', 'group', 'description'];

    /**
     * @var string[]
     */
    private const SUPPORTED_METHODS = ['addTcaSelectItem', 'addRecordType', 'addPlugin'];

    /**
     * @readonly
     */
    private PhpVersionProvider $phpVersionProvider;

    public function __construct(PhpVersionProvider $phpVersionProvider)
    {
        $this->phpVersionProvider = $phpVersionProvider;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use SelectItem instead of Array', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'my_field',
    [
        'my-label',
        'my-value',
        'my-icon',
        'my-group',
        'my-description',
    ]
);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Schema\Struct\SelectItem;

ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'my_field',
    new SelectItem(
        'select',
        'my-label',
        'my-value',
        'my-icon',
        'my-group',
        'my-description',
    )
);
CODE_SAMPLE
            ),
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addRecordType(
    [
        'my-label',
        'my-value',
        'my-icon',
        'my-group',
        'my-description',
    ]
);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Schema\Struct\SelectItem;

ExtensionManagementUtility::addRecordType(
    new SelectItem(
        'select',
        'my-label',
        'my-value',
        'my-icon',
        'my-group',
        'my-description',
    )
);
CODE_SAMPLE
            ),
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addPlugin(
    [
        'my-label',
        'my-value',
        'my-icon',
        'my-group',
        'my-description',
    ]
);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Schema\Struct\SelectItem;

ExtensionManagementUtility::addPlugin(
    new SelectItem(
        'select',
        'my-label',
        'my-value',
        'my-icon',
        'my-group',
        'my-description',
    )
);
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
        if (! $this->isName($node->class, 'TYPO3\CMS\Core\Utility\ExtensionManagementUtility')) {
            return null;
        }

        $methodName = $this->getName($node->name);
        if (! in_array($methodName, self::SUPPORTED_METHODS, true)) {
            return null;
        }

        $args = $node->getArgs();
        $itemArgIndex = $methodName === 'addTcaSelectItem' ? 2 : 0;

        if (! isset($args[$itemArgIndex])) {
            return null;
        }

        $itemArrayNode = $args[$itemArgIndex]->value;
        if (! $itemArrayNode instanceof Array_) {
            return null;
        }

        if ($itemArrayNode->items === []) {
            return null;
        }

        $selectItemNew = $this->createSelectItemNew($itemArrayNode);
        if (! $selectItemNew instanceof New_) {
            return null;
        }

        $args[$itemArgIndex]->value = $selectItemNew;

        return $node;
    }

    private function createSelectItemNew(Array_ $itemArrayNode): ?New_
    {
        $itemValues = $this->resolveItemValues($itemArrayNode);
        if ($itemValues === null) {
            return null;
        }

        $constructorArgs = [];
        $useNamedArguments = $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::NAMED_ARGUMENTS);

        foreach (self::SELECT_ITEM_KEYS as $key) {
            if (! array_key_exists($key, $itemValues)) {
                continue;
            }

            $argName = $useNamedArguments ? new Identifier($key) : null;
            $constructorArgs[] = new Arg($itemValues[$key], false, false, [], $argName);
        }

        return new New_(new FullyQualified('TYPO3\CMS\Core\Schema\Struct\SelectItem'), $constructorArgs);
    }

    /**
     * @return array<string, Node\Expr>|null
     */
    private function resolveItemValues(Array_ $itemArrayNode): ?array
    {
        $itemValues = [];

        foreach ($itemArrayNode->items as $index => $item) {
            if (! $item instanceof ArrayItem) {
                continue;
            }

            $key = $this->resolveItemKey($item, $index);
            if ($key === null) {
                continue;
            }

            if (! in_array($key, self::SELECT_ITEM_KEYS, true)) {
                continue;
            }

            $itemValues[$key] = $item->value;
        }

        if (! isset($itemValues['label'], $itemValues['value'])) {
            return null;
        }

        $itemValues['type'] ??= new String_(self::SELECT_ITEM_TYPE);

        return $itemValues;
    }

    private function resolveItemKey(ArrayItem $item, int $index): ?string
    {
        if (! $item->key instanceof Expr) {
            return self::LEGACY_INDEXED_KEYS[$index] ?? null;
        }

        if ($item->key instanceof String_) {
            return $item->key->value;
        }

        if ($item->key instanceof Int_) {
            return self::LEGACY_INDEXED_KEYS[$item->key->value] ?? null;
        }

        return null;
    }
}
