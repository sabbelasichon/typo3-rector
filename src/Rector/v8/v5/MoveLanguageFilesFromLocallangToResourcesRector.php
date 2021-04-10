<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v5;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.5/Deprecation-78647-MoveLanguageFilesFromEXTlanglocallang_ToResourcesPrivateLanguage.html
 */
final class MoveLanguageFilesFromLocallangToResourcesRector extends AbstractRector
{
    /**
     * @var string[]
     */
    private const MAPPING_OLD_TO_NEW_PATHS = [
        'LLL:EXT:lang/locallang_alt_doc.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_alt_doc.xlf',
        'LLL:EXT:lang/locallang_alt_intro.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_alt_intro.xlf',
        'LLL:EXT:lang/locallang_browse_links.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_browse_links.xlf',
        'LLL:EXT:lang/locallang_common.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_common.xlf',
        'LLL:EXT:lang/locallang_core.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf',
        'LLL:EXT:lang/locallang_csh_be_groups.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_csh_be_groups.xlf',
        'LLL:EXT:lang/locallang_csh_be_users.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_csh_be_users.xlf',
        'LLL:EXT:lang/locallang_csh_corebe.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_csh_corebe.xlf',
        'LLL:EXT:lang/locallang_csh_pages.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_csh_pages.xlf',
        'LLL:EXT:lang/locallang_csh_sysfilem.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_csh_sysfilem.xlf',
        'LLL:EXT:lang/locallang_csh_syslang.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_csh_syslang.xlf',
        'LLL:EXT:lang/locallang_csh_sysnews.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_csh_sysnews.xlf',
        'LLL:EXT:lang/locallang_csh_web_func.xlf' => 'func/Resources/Private/Language/locallang_csh_web_func.xlf',
        'LLL:EXT:lang/locallang_csh_web_info.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_csh_web_info.xlf',
        'LLL:EXT:lang/locallang_general.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf',
        'LLL:EXT:lang/locallang_general.xml' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf',
        'LLL:EXT:lang/locallang_general.php' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf',
        'LLL:EXT:lang/locallang_login.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_login.xlf',
        'LLL:EXT:lang/locallang_misc.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_misc.xlf',
        'LLL:EXT:lang/locallang_mod_admintools.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_mod_admintools.xlf',
        'LLL:EXT:lang/locallang_mod_file_list.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_mod_file_list.xlf',
        'LLL:EXT:lang/locallang_mod_file.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_mod_file.xlf',
        'LLL:EXT:lang/locallang_mod_help_about.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_mod_help_about.xlf',
        'LLL:EXT:lang/locallang_mod_help_cshmanual.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_mod_help_cshmanual.xlf',
        'LLL:EXT:lang/locallang_mod_help.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_mod_help.xlf',
        'LLL:EXT:lang/locallang_mod_system.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_mod_system.xlf',
        'LLL:EXT:lang/locallang_mod_usertools.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_mod_usertools.xlf',
        'LLL:EXT:lang/locallang_mod_user_ws.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_mod_user_ws.xlf',
        'LLL:EXT:lang/locallang_mod_web_func.xlf' => 'LLL:EXT:func/Resources/Private/Language/locallang_mod_web_func.xlf',
        'LLL:EXT:lang/locallang_mod_web_info.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_mod_web_info.xlf',
        'LLL:EXT:lang/locallang_mod_web_list.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_mod_web_list.xlf',
        'LLL:EXT:lang/locallang_mod_web.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_mod_web.xlf',
        'LLL:EXT:lang/locallang_show_rechis.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_show_rechis.xlf',
        'LLL:EXT:lang/locallang_t3lib_fullsearch.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_t3lib_fullsearch.xlf',
        'LLL:EXT:lang/locallang_tca.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf',
        'LLL:EXT:lang/locallang_tcemain.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_tcemain.xlf',
        'LLL:EXT:lang/locallang_tsfe.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_tsfe.xlf',
        'LLL:EXT:lang/locallang_tsparser.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_tsparser.xlf',
        'LLL:EXT:lang/locallang_view_help.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_view_help.xlf',
        'LLL:EXT:lang/locallang_wizards.xlf' => 'LLL:EXT:lang/Resources/Private/Language/locallang_wizards.xlf',
    ];

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [String_::class];
    }

    /**
     * @param String_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $value = $this->valueResolver->getValue($node);

        foreach (self::MAPPING_OLD_TO_NEW_PATHS as $oldPath => $newPath) {
            if (Strings::contains($value, $oldPath)) {
                return new String_(str_replace($oldPath, $newPath, $value));
            }
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Move language files from EXT:lang/locallang_* to Resources/Private/Language', [
            new CodeSample(<<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Localization\LanguageService;
$languageService = new LanguageService();
$languageService->sL('LLL:EXT:lang/locallang_alt_doc.xlf:label.confirm.delete_record.title');
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Localization\LanguageService;
$languageService = new LanguageService();
$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_alt_doc.xlf:label.confirm.delete_record.title');
CODE_SAMPLE
            ),
        ]);
    }
}
