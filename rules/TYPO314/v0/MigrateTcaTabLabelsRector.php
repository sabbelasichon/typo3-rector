<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Important-107789-TCATabLabelsConsolidatedIntoCoreFormTabs.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateTcaTabLabelsRector\MigrateTcaTabLabelsRectorTest
 */
final class MigrateTcaTabLabelsRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var string
     */
    private const NEW_TAB_LABEL_PREFIX = 'core.form.tabs:';

    /**
     * @var string
     */
    private const TABS_PATTERN = '/(LLL:EXT:core\/Resources\/Private\/Language\/Form\/locallang_tabs\.xlf:)(\w+)/';

    /**
     * @var array<string, string>
     */
    private const TCA_LABEL_MAP = [
        // be_groups
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.record_permissions' => 'core.form.tabs:recordpermissions',
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.module_permissions' => 'core.form.tabs:modulepermissions',
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.mounts_and_workspaces' => 'core.form.tabs:mounts',
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.options' => 'core.form.tabs:options',

        // be_users
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.personal_data' => 'core.form.tabs:personaldata',
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.permissionRecords' => 'core.form.tabs:recordpermissions',
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.permissions' => 'core.form.tabs:modulepermissions',
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.mounts_and_workspaces' => 'core.form.tabs:mounts',
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.options' => 'core.form.tabs:options',

        // sys_category
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.items' => 'core.form.tabs:items',

        // pages
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata' => 'core.form.tabs:metadata',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.appearance' => 'core.form.tabs:appearance',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.behaviour' => 'core.form.tabs:behaviour',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.resources' => 'core.form.tabs:resources',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access' => 'core.form.tabs:access',

        // fe_users
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:fe_users.tabs.personalData' => 'core.form.tabs:personaldata',

        // sys_template
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:sys_template.tabs.options' => 'core.form.tabs:personaldata',

        // filemetadata
        'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.metadata' => 'core.form.tabs:metadata',
        'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.camera' => 'core.form.tabs:camera',
        'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.audio' => 'core.form.tabs:audio',
        'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.video' => 'core.form.tabs:video',

        // seo
        'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.tabs.seo' => 'core.form.tabs:seo',
        'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.tabs.socialmedia' => 'core.form.tabs:socialmedia',

        // redirects
        'LLL:EXT:redirects/Resources/Private/Language/locallang_db.xlf:tabs.redirectCount' => 'redirects.tabs:redirectCount',

        // webhooks
        'LLL:EXT:webhooks/Resources/Private/Language/locallang_db.xlf:palette.http_settings' => 'webhooks.db:palette.http_settings',

        // workspaces
        'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.internal_stages' => 'workspaces.db:tabs.internal_stages',
        'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.notification_settings' => 'workspaces.db:tabs.notification_settings',
        'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.custom_stages' => 'workspaces.db:tabs.custom_stages',
        'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.mountpoints' => 'workspaces.db:tabs.mountpoints',
        'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.publish_access' => 'workspaces.db:tabs.publish_access',
    ];

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
        return new RuleDefinition('Migrate TCA tab labels into core.form.tabs', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'types' => [
        '0' => ['showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.personal_data,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
            --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.items,
        '],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'types' => [
        '0' => ['showitem' => '
            --div--;core.form.tabs:general,
            --div--;core.form.tabs:personaldata,
            --div--;core.form.tabs:metadata,
            --div--;core.form.tabs:items,
        '],
    ],
];
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ArrayItem::class];
    }

    /**
     * @param ArrayItem $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->key === null) {
            return null;
        }

        if (! $node->value instanceof String_) {
            return null;
        }

        if (! $this->valueResolver->isValue($node->key, 'showitem')) {
            return null;
        }

        $oldShowitemString = $node->value->value;
        $newShowitemString = $oldShowitemString;

        // Apply the specific, hardcoded mappings from locallang_tca.xlf / locallang_db.xlf
        $newShowitemString = str_replace(
            array_keys(self::TCA_LABEL_MAP),
            array_values(self::TCA_LABEL_MAP),
            $newShowitemString
        );

        // Apply the general pattern for locallang_tabs.xlf
        $newShowitemString = preg_replace(
            self::TABS_PATTERN,
            self::NEW_TAB_LABEL_PREFIX . '$2',
            $newShowitemString
        );

        if ($newShowitemString === null || $newShowitemString === $oldShowitemString) {
            return null;
        }

        $node->value = new String_($newShowitemString, $node->value->getAttributes());

        return $node;
    }
}
