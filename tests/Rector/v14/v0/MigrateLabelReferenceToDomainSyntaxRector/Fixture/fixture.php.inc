<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateLabelReferenceToDomainSyntaxRector\Fixture;

use TYPO3\CMS\Core\Localization\LanguageService;

$lang = new LanguageService();

// Standard translation mapping to .messages
$label = $lang->sL('LLL:EXT:core/Resources/Private/Language/locallang.xlf:labels.save');

// Module mapping to .module
$label = $lang->sL('LLL:EXT:core/Resources/Private/Language/locallang_module.xlf:mlang_tabs_tab');

// Subdirectory mapping to .backend.dashboard
$label = $lang->sL('LLL:EXT:my_blog/Resources/Private/Language/Backend/locallang_dashboard.xlf:title');

// Site Set mapping to .sets.blog
$label = $lang->sL('LLL:EXT:news_comments/Configuration/Sets/Blog/labels.xlf:set_title');

// Case conversion from UpperCamelCase to snake_case
$label = $lang->sL('LLL:EXT:site_ext/Resources/Private/Language/UserProfile/locallang.xlf:name');

// Locale prefix stripping (de.locallang becomes .messages)
$label = $lang->sL('LLL:EXT:core/Resources/Private/Language/de.locallang.xlf:labels.open');

$label = $lang->sL('LLL:EXT:core/Resources/Private/Language/locallang_command.xlf:permissionset.check.preset_not_applied');

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateLabelReferenceToDomainSyntaxRector\Fixture;

use TYPO3\CMS\Core\Localization\LanguageService;

$lang = new LanguageService();

// Standard translation mapping to .messages
$label = $lang->sL('core.messages:labels.save');

// Module mapping to .module
$label = $lang->sL('core.module:mlang_tabs_tab');

// Subdirectory mapping to .backend.dashboard
$label = $lang->sL('my_blog.backend.dashboard:title');

// Site Set mapping to .sets.blog
$label = $lang->sL('news_comments.sets.blog:set_title');

// Case conversion from UpperCamelCase to snake_case
$label = $lang->sL('site_ext.user_profile.messages:name');

// Locale prefix stripping (de.locallang becomes .messages)
$label = $lang->sL('core.messages:labels.open');

$label = $lang->sL('core.command:permissionset.check.preset_not_applied');

?>
