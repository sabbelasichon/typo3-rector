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
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-107789-CoreTCATabLabelsUseShortFormReferences.html
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Important-107789-TCATabLabelsConsolidatedIntoCoreFormTabs.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateCoreTcaAndUserSettingsShowitemStringsToShortFormReferencesRector\MigrateCoreTcaAndUserSettingsShowitemStringsToShortFormReferencesRectorTest
 */
final class MigrateCoreTcaAndUserSettingsShowitemStringsToShortFormReferencesRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var array<string, string>
     */
    private const TCA_SHOWITEM_MAP = [
        ///// Tab labels (--div--):
        '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general' => '--div--;core.form.tabs:general',
        '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access' => '--div--;core.form.tabs:access',
        '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language' => '--div--;core.form.tabs:language',
        '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes' => '--div--;core.form.tabs:notes',
        '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended' => '--div--;core.form.tabs:extended',
        '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories' => '--div--;core.form.tabs:categories',
        '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:appearance' => '--div--;core.form.tabs:appearance',

        '--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.record_permissions' => '--div--;core.form.tabs:recordpermissions',
        '--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.module_permissions' => '--div--;core.form.tabs:modulepermissions',
        '--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.mounts_and_workspaces' => '--div--;core.form.tabs:mounts',
        '--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.options' => '--div--;core.form.tabs:options',

        '--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.personal_data' => '--div--;core.form.tabs:personaldata',
        '--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.permissionRecords' => '--div--;core.form.tabs:recordpermissions',
        '--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.permissions' => '--div--;core.form.tabs:modulepermissions',
        '--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.mounts_and_workspaces' => '--div--;core.form.tabs:mounts',
        '--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.options' => '--div--;core.form.tabs:options',

        '--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.items' => '--div--;core.form.tabs:items',

        // EXT:filemetadata
        '--div--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.metadata' => '--div--;core.form.tabs:metadata',
        '--div--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.camera' => '--div--;core.form.tabs:camera',
        '--div--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.audio' => '--div--;core.form.tabs:audio',
        '--div--;LLL:EXT:filemetadata/Resources/Private/Language/locallang_tca.xlf:tabs.video' => '--div--;core.form.tabs:video',

        // EXT:frontend
        '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata' => '--div--;core.form.tabs:metadata',
        '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.appearance' => '--div--;core.form.tabs:appearance',
        '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.behaviour' => '--div--;core.form.tabs:behaviour',
        '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.resources' => '--div--;core.form.tabs:resources',
        '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access' => '--div--;core.form.tabs:access',

        '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:fe_users.tabs.personalData' => '--div--;core.form.tabs:personaldata',

        '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:sys_template.tabs.options' => '--div--;core.form.tabs:advancedoptions',

        '--div--;LLL:EXT:redirects/Resources/Private/Language/locallang_db.xlf:tabs.redirectCount' => '--div--;redirects.tabs:redirectCount',

        '--div--;LLL:EXT:setup/Resources/Private/Language/locallang.xlf:personal_data' => '--div--;core.form.tabs:personaldata',
        '--div--;LLL:EXT:setup/Resources/Private/Language/locallang.xlf:accountSecurity' => '--div--;core.form.tabs:account_security',
        '--div--;LLL:EXT:setup/Resources/Private/Language/locallang.xlf:opening' => '--div--;core.form.tabs:backend_appearance',
        '--div--;LLL:EXT:setup/Resources/Private/Language/locallang.xlf:personalization' => '--div--;core.form.tabs:personalization',
        '--div--;LLL:EXT:setup/Resources/Private/Language/locallang.xlf:resetTab' => '--div--;core.form.tabs:reset_configuration',

        '--div--;LLL:EXT:webhooks/Resources/Private/Language/locallang_db.xlf:palette.http_settings' => '--div--;webhooks.db:palette.http_settings',

        '--div--;LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.internal_stages' => '--div--;workspaces.db:tabs.internal_stages',
        '--div--;LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.custom_stages' => '--div--;workspaces.db:tabs.custom_stages',
        '--div--;LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.mountpoints' => '--div--;workspaces.db:tabs.mountpoints',
        '--div--;LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.publish_access' => '--div--;workspaces.db:tabs.publish_access',

        // EXT:seo
        '--div--;LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.tabs.seo' => '--div--;core.form.tabs:seo',
        '--div--;LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.tabs.socialmedia' => '--div--;core.form.tabs:socialmedia',

        // Palette
        '--palette--;LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.notification_settings' => '--palette--;workspaces.db:tabs.notification_settings',

        // Fields
        'bodytext;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext_formlabel' => 'bodytext',
        'bodytext;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:field.table.bodytext' => 'bodytext',
        'CType;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:CType_formlabel' => 'CType',
        'colPos;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:colPos_formlabel' => 'colPos',
        'header;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_formlabel' => 'header',
        'header_layout;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_layout_formlabel' => 'header_layout',
        'header_link;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_link_formlabel' => 'header_link',
        'header_position;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_position_formlabel' => 'header_position',
        'subheader;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:subheader_formlabel' => 'subheader',
        'date;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:date_formlabel' => 'date',
        'file_collections;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:file_collections.ALT.uploads_formlabel' => 'file_collections',
        'filelink_size;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:filelink_size_formlabel' => 'filelink_size',
        'image_zoom;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:image_zoom_formlabel' => 'image_zoom',
        'imageborder;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.mediaAdjustments.imageborder' => 'imageborder',
        'image*;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imageborder_formlabel' => 'frontend.db.tt_content:imageborder',
        'imagecols;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imagecols_formlabel' => 'imagecols',
        'imageorient;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imageorient_formlabel' => 'imageorient',
        'imageheight;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.mediaAdjustments.imageheight' => 'imageheight',
        'imagewidth;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.mediaAdjustments.imagewidth' => 'imagewidth',
        'frame_class;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:frame_class_formlabel' => 'frame_class',
        'starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.starttime_formlabel' => 'starttime',
        'endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.endtime_formlabel' => 'endtime',
        'fe_group;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.fe_group_formlabel' => 'fe_group',
        'media;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.ALT.uploads_formlabel' => 'media',
        'sectionIndex;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:sectionIndex_formlabel' => 'sectionIndex',
        'linkToTop;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:linkToTop_formlabel' => 'linkToTop',
        'layout;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:layout_formlabel' => 'layout',
        'space_before_class;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_before_class_formlabel' => 'space_before_class',
        'space_after_class;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_after_class_formlabel' => 'space_after_class',
        'doktype;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.doktype_formlabel' => 'doktype',
        'shortcut_mode;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.shortcut_mode_formlabel' => 'shortcut_mode',
        'shortcut;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.shortcut_formlabel' => 'shortcut',
        'mount_pid_ol;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.mount_pid_ol_formlabel' => 'mount_pid_ol',
        'mount_pid;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.mount_pid_formlabel' => 'mount_pid',
        'url;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.url_formlabel' => 'url',
        'title;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.title_formlabel' => 'title',
        'nav_title;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.nav_title_formlabel' => 'nav_title',
        'subtitle;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.subtitle_formlabel' => 'subtitle',
        'nav_hide;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.nav_hide_toggle_formlabel' => 'nav_hide',
        'extendToSubpages;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.extendToSubpages_formlabel' => 'extendToSubpages',
        'abstract;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.abstract_formlabel' => 'abstract',
        'keywords;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.keywords_formlabel' => 'keywords',
        'author;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.author_formlabel' => 'author',
        'author_email;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.author_email_formlabel' => 'author_email',
        'lastUpdated;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.lastUpdated_formlabel' => 'lastUpdated',
        'newUntil;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.newUntil_formlabel' => 'newUntil',
        'backend_layout;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.backend_layout_formlabel' => 'backend_layout',
        'backend_layout_next_level;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.backend_layout_next_level_formlabel' => 'backend_layout_next_level',
        'module;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.module_formlabel' => 'module',
        'content_from_pid;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.content_from_pid_formlabel' => 'content_from_pid',
        'cache_timeout;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.cache_timeout_formlabel' => 'cache_timeout',
        'l18n_cfg;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.l18n_cfg_formlabel' => 'l18n_cfg',
        'is_siteroot;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.is_siteroot_formlabel' => 'is_siteroot',
        'no_search;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.no_search_formlabel' => 'no_search',
        'php_tree_stop;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.php_tree_stop_formlabel' => 'php_tree_stop',
        'editlock;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.editlock_formlabel' => 'editlock',
        'media;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.media_formlabel' => 'media',
        'tsconfig_includes;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tsconfig_includes' => 'tsconfig_includes',
        'TSconfig;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.TSconfig_formlabel' => 'TSconfig',

        // these fields don't have a column definition
        'hidden;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.hidden_toggle_formlabel' => 'hidden;core.db.pages:hidden',
        'hidden;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.hidden_formlabel' => 'hidden;core.db.pages:hidden',
        'hidden;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:field.default.hidden' => 'hidden;frontend.db.tt_content:hidden',
        'starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel' => 'starttime;core.db.general:starttime',
        'endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel' => 'endtime;core.db.general:endtime',
        'fe_group;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:fe_group_formlabel' => 'fe_group;core.db.general:fe_group',
        'target;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.target_formlabel' => 'target;core.db.pages:link.target',
    ];

    /**
     * @var array<string, string>
     */
    private const TCA_LABEL_MAP = [
        //'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.type' => 'frontend.db.tt_content:type', -> this is used for CType and header_layout
        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.layout' => 'frontend.db.tt_content:layout',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:frame_class' => 'frontend.db.tt_content:frame_class',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_before_class' => 'frontend.db.tt_content:space_before_class',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_after_class' => 'frontend.db.tt_content:space_after_class',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:colPos' => 'frontend.db.tt_content:column',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:date' => 'frontend.db.tt_content:date',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header' => 'frontend.db.tt_content:header',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_position' => 'frontend.db.tt_content:header_position',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_link' => 'frontend.db.tt_content:header_link',
        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.subheader' => 'frontend.db.tt_content:subheader',
        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.text' => 'frontend.db.tt_content:bodytext',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imagewidth' => 'frontend.db.tt_content:imagewidth',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imageheight' => 'frontend.db.tt_content:imageheight',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imageorient' => 'frontend.db.tt_content:imageorientation',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imageborder' => 'frontend.db.tt_content:imageborder',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:image_zoom' => 'frontend.db.tt_content:image_zoom',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imagecols' => 'frontend.db.tt_content:imagecols',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:sectionIndex' => 'frontend.db.tt_content:section_index',
        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:linkToTop' => 'frontend.db.tt_content:link_to_top',

        // be_groups
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.record_permissions' => 'core.form.tabs:recordpermissions',
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.module_permissions' => 'core.form.tabs:modulepermissions',
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.mounts_and_workspaces' => 'core.form.tabs:mounts',
        'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_groups.tabs.options' => 'core.form.tabs:options',

        // be_users
        #'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:be_users.tabs.personal_data' => 'core.form.tabs:personaldata',
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

        // redirects
        'LLL:EXT:redirects/Resources/Private/Language/locallang_db.xlf:tabs.redirectCount' => 'redirects.tabs:redirectCount',

        // workspaces
        'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.internal_stages' => 'workspaces.db:tabs.internal_stages',
        'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.notification_settings' => 'workspaces.db:tabs.notification_settings',
        'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.custom_stages' => 'workspaces.db:tabs.custom_stages',
        'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.mountpoints' => 'workspaces.db:tabs.mountpoints',
        'LLL:EXT:workspaces/Resources/Private/Language/locallang_db.xlf:tabs.publish_access' => 'workspaces.db:tabs.publish_access',

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
        ), new CodeSample(
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

        if ($this->valueResolver->isValues($node->key, ['label', 'description'])) {
            $oldLabel = $node->value->value;

            if (isset(self::TCA_LABEL_MAP[$oldLabel])) {
                $newLabel = self::TCA_LABEL_MAP[$oldLabel];
                $node->value = new String_($newLabel, $node->value->getAttributes());
                return $node;
            }
        }

        if ($this->valueResolver->isValue($node->key, 'showitem')) {
            $oldShowitemString = $node->value->value;
            $newShowitemString = $oldShowitemString;
            $newShowitemString = str_replace(
                array_keys(self::TCA_SHOWITEM_MAP),
                array_values(self::TCA_SHOWITEM_MAP),
                $newShowitemString
            );

            if ($newShowitemString === $oldShowitemString) {
                return null;
            }

            $node->value = new String_($newShowitemString, $node->value->getAttributes());

            return $node;
        }

        return null;
    }
}
