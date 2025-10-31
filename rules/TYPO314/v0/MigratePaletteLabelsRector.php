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
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107790-TCAPaletteLabelsConsolidatedIntoCoreFormPalettes.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\MigratePaletteLabelsRectorTest
 */
final class MigratePaletteLabelsRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var array<string, string>
     */
    private const PALETTE_LABEL_MAP = [
        // be_groups
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.palettes.authentication' => 'core.form.palettes:authentication',
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.palettes.permissionGeneral' => 'core.form.palettes:permission_general',
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.palettes.permissionLanguages' => 'core.form.palettes:permission_languages',
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.palettes.permissionSpecific' => 'core.form.palettes:permission_specific',

        // be_users
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.palettes.account' => 'core.form.palettes:account',
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.palettes.authentication' => 'core.form.palettes:authentication',
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.palettes.permissionLanguages' => 'core.form.palettes:permission_languages',

        // pages
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.access' => 'core.form.palettes:access',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.fe_related_pages' => 'core.form.palettes:fe_related_pages',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.abstract' => 'core.form.palettes:abstract',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.metatags' => 'core.form.palettes:metatags',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.keywords' => 'core.form.palettes:keywords',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.publishing' => 'core.form.palettes:publishing',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.storage' => 'core.form.palettes:storage',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.standard' => 'core.form.palettes:standard',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.title' => 'core.form.palettes:title',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.visibility' => 'core.form.palettes:visibility',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.editorial' => 'core.form.palettes:editorial',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.layout' => 'core.form.palettes:layout',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.module' => 'core.form.palettes:use_as_container',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.replace' => 'core.form.palettes:replace',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.links' => 'core.form.palettes:links_page',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.caching' => 'core.form.palettes:caching',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.language' => 'core.form.palettes:language',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.miscellaneous' => 'core.form.palettes:miscellaneous',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.media' => 'core.form.palettes:media',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.config' => 'core.form.palettes:config',

        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general' => 'core.form.palettes:general',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.header' => 'core.form.palettes:header',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers' => 'core.form.palettes:headers',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.gallerySettings' => 'core.form.palettes:settings_gallery',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.mediaAdjustments' => 'core.form.palettes:media_adjustments',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.imagelinks' => 'core.form.palettes:media_behaviour',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access' => 'core.form.palettes:access',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks' => 'core.form.palettes:links_appearance',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames' => 'core.form.palettes:content_layout',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.table_layout' => 'core.form.palettes:table_layout',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.uploads_layout' => 'core.form.palettes:downloads_layout',

        // filemetadata
        'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.accessibility' => 'core.form.palettes:accessibility',
        'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.gps' => 'core.form.palettes:gps',
        'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.geo_location' => 'core.form.palettes:geolocation',
        'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.metrics' => 'core.form.palettes:metrics',
        'LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:palette.content_date' => 'core.form.palettes:contentdate',

        // reactions
        'LLL:EXT:reactions/Resources/Private/Language/locallang_db.xlf:palette.additional' => 'reactions.db:palette.additional',
        'LLL:EXT:reactions/Resources/Private/Language/locallang_db.xlf:palette.config' => 'reactions.db:palette.config',
        'LLL:EXT:reactions/Resources/Private/Language/locallang_db.xlf:palette.config.description' => 'reactions.db:palette.config.description',

        // seo
        'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.palettes.seo' => 'core.form.palettes:seo',
        'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.palettes.robots' => 'core.form.palettes:robots',
        'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.palettes.canonical' => 'core.form.palettes:canonical',
        'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.palettes.sitemap' => 'core.form.palettes:sitemap',
        'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.palettes.opengraph' => 'core.form.palettes:opengraph',
        'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.palettes.twittercards' => 'core.form.palettes:twittercards',

        // webhooks
        'LLL:EXT:webhooks/Resources/Private/Language/locallang_db.xlf:palette.config' => 'webhooks.db:palette.config',
        'LLL:EXT:webhooks/Resources/Private/Language/locallang_db.xlf:palette.config.description' => 'webhooks.db:palette.config.description',
        'LLL:EXT:webhooks/Resources/Private/Language/locallang_db.xlf:palette.http_settings' => 'webhooks.db:palette.http_settings',
        'LLL:EXT:webhooks/Resources/Private/Language/locallang_db.xlf:palette.http_settings.description' => 'webhooks.db:palette.http_settings.description',

        // workspaces
        'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.users' => 'workspaces.db:tabs.users',
        'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:sys_workspace.palette.stage.edit' => 'workspaces.db:sys_workspace.palette.stage.edit',
        'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:sys_workspace.palette.stage.publish' => 'workspaces.db:sys_workspace.palette.stage.publish',
        'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:sys_workspace.palette.stage.execute' => 'workspaces.db:sys_workspace.palette.stage.execute',
    ];

    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate TCA palette labels into core.form.palettes', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'palettes' => [
        'authentication' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.palettes.authentication',
            'showitem' => 'mfa_providers',
        ],
        'description' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file.palettes.description',
            'showitem' => 'description',
        ],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'palettes' => [
        'authentication' => [
            'label' => 'core.form.palettes:authentication',
            'showitem' => 'mfa_providers',
        ],
        'description' => [
            'label' => 'core.form.palettes:description',
            'showitem' => 'description',
        ],
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

        if (! $this->valueResolver->isValues($node->key, ['label', 'description'])) {
            return null;
        }

        $oldLabel = $node->value->value;

        if (isset(self::PALETTE_LABEL_MAP[$oldLabel])) {
            $newLabel = self::PALETTE_LABEL_MAP[$oldLabel];
            $node->value = new String_($newLabel, $node->value->getAttributes());
            return $node;
        }

        return null;
    }
}
