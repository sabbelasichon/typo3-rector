<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\MoveLanguageFilesFromExtensionLangRector\Fixture;
use TYPO3\CMS\Core\Localization\LanguageService;

/** Keep it in the comments LLL:EXT:lang/Resources/Private/Language/locallang_alt_intro.xlf */

class MySpecialClass
{
    protected $itemsConfiguration = [
        'edit' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:cm.edit',
            'iconIdentifier' => 'actions-page-open',
            'callbackAction' => 'editFile'
        ],
        'rename' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:cm.rename',
            'iconIdentifier' => 'actions-edit-rename',
            'callbackAction' => 'renameFile'
        ],
        'upload' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:cm.upload',
            'iconIdentifier' => 'actions-edit-upload',
            'callbackAction' => 'uploadFile'
        ],
        'new' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:cm.new',
            'iconIdentifier' => 'actions-document-new',
            'callbackAction' => 'createFile'
        ],
        'info' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:cm.info',
            'iconIdentifier' => 'actions-document-info',
            'callbackAction' => 'openInfoPopUp'
        ],
        'divider' => [
            'type' => 'divider'
        ],
        'copy' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:cm.copy',
            'iconIdentifier' => 'actions-edit-copy',
            'callbackAction' => 'copyFile'
        ],
        'copyRelease' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:cm.copy',
            'iconIdentifier' => 'actions-edit-copy-release',
            'callbackAction' => 'copyReleaseFile'
        ],
        'cut' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:cm.cut',
            'iconIdentifier' => 'actions-edit-cut',
            'callbackAction' => 'cutFile'
        ],
        'cutRelease' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:cm.cutrelease',
            'iconIdentifier' => 'actions-edit-cut-release',
            'callbackAction' => 'cutReleaseFile'
        ],
        'pasteInto' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:cm.pasteinto',
            'iconIdentifier' => 'actions-document-paste-into',
            'callbackAction' => 'pasteFileInto'
        ],
        'divider2' => [
            'type' => 'divider'
        ],
        'delete' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:cm.delete',
            'iconIdentifier' => 'actions-edit-delete',
            'callbackAction' => 'deleteFile'
        ],
    ];

    /**
     * @var string
     */
    private $labels = 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:';

    protected function getLabel($key)
    {
        return sprintf($this->labels . $key);
    }
}

$languageService = new LanguageService();
$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_alt_intro.xlf');
$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_alt_doc.xlf');
$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_login.xlf');
$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_common.xlf');
$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf');
$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf');
$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_misc.xlf');
$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_mod_web_list.xlf');
$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf');
$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_tsfe.xlf');
$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_wizards.xlf');
$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_browse_links.xlf');
$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_tcemain.xlf');

$coreLabels = 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:';
$languageService->sL($coreLabels.'labels.reload');

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\MoveLanguageFilesFromExtensionLangRector\Fixture;
use TYPO3\CMS\Core\Localization\LanguageService;

/** Keep it in the comments LLL:EXT:lang/Resources/Private/Language/locallang_alt_intro.xlf */

class MySpecialClass
{
    protected $itemsConfiguration = [
        'edit' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:cm.edit',
            'iconIdentifier' => 'actions-page-open',
            'callbackAction' => 'editFile'
        ],
        'rename' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:cm.rename',
            'iconIdentifier' => 'actions-edit-rename',
            'callbackAction' => 'renameFile'
        ],
        'upload' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:cm.upload',
            'iconIdentifier' => 'actions-edit-upload',
            'callbackAction' => 'uploadFile'
        ],
        'new' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:cm.new',
            'iconIdentifier' => 'actions-document-new',
            'callbackAction' => 'createFile'
        ],
        'info' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:cm.info',
            'iconIdentifier' => 'actions-document-info',
            'callbackAction' => 'openInfoPopUp'
        ],
        'divider' => [
            'type' => 'divider'
        ],
        'copy' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:cm.copy',
            'iconIdentifier' => 'actions-edit-copy',
            'callbackAction' => 'copyFile'
        ],
        'copyRelease' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:cm.copy',
            'iconIdentifier' => 'actions-edit-copy-release',
            'callbackAction' => 'copyReleaseFile'
        ],
        'cut' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:cm.cut',
            'iconIdentifier' => 'actions-edit-cut',
            'callbackAction' => 'cutFile'
        ],
        'cutRelease' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:cm.cutrelease',
            'iconIdentifier' => 'actions-edit-cut-release',
            'callbackAction' => 'cutReleaseFile'
        ],
        'pasteInto' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:cm.pasteinto',
            'iconIdentifier' => 'actions-document-paste-into',
            'callbackAction' => 'pasteFileInto'
        ],
        'divider2' => [
            'type' => 'divider'
        ],
        'delete' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:cm.delete',
            'iconIdentifier' => 'actions-edit-delete',
            'callbackAction' => 'deleteFile'
        ],
    ];

    /**
     * @var string
     */
    private $labels = 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:';

    protected function getLabel($key)
    {
        return sprintf($this->labels . $key);
    }
}

$languageService = new LanguageService();
$languageService->sL('LLL:EXT:about/Resources/Private/Language/Modules/locallang_alt_intro.xlf');
$languageService->sL('LLL:EXT:backend/Resources/Private/Language/locallang_alt_doc.xlf');
$languageService->sL('LLL:EXT:backend/Resources/Private/Language/locallang_login.xlf');
$languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_common.xlf');
$languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf');
$languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_general.xlf');
$languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_misc.xlf');
$languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_mod_web_list.xlf');
$languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf');
$languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_tsfe.xlf');
$languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf');
$languageService->sL('LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf');
$languageService->sL('LLL:EXT:workspaces/Resources/Private/Language/locallang_tcemain.xlf');

$coreLabels = 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:';
$languageService->sL($coreLabels.'labels.reload');

?>
