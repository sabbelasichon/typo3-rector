<?php

declare(strict_types=1);

namespace TYPO3\CMS\Frontend\Controller;

use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Page\PageRepository;

if (class_exists(TypoScriptFrontendController::class)) {
    return;
}

final class TypoScriptFrontendController
{
    /**
     * @var array
     */
    public $cHash_array = [];

    /**
     * @var string
     */
    public $cHash = '';

    /**
     * @var int
     */
    public $domainStartPage = 0;

    /**
     * @var string
     */
    public $xhtmlDoctype = '';

    /**
     * @var ContentObjectRenderer
     */
    public $cObj = '';

    /**
     * @var string
     */
    public $loginUser = '';

    /**
     * @var string
     */
    public $gr_list = '';

    /**
     * @var string
     */
    public $beUserLogin = '';

    /**
     * @var string
     */
    public $showHiddenPage = '';

    /**
     * @var string
     */
    public $showHiddenRecords = '';

    /**
     * @var bool
     */
    public $forceTemplateParsing = false;

    /**
     * @var TemplateService
     */
    public $tmpl;

    /**
     * @var PageRepository
     */
    public $sys_page;

    /**
     * @var SiteLanguage
     */
    protected $language;

    /**
     * @var string
     */
    public $sys_language_isocode;

    /**
     * @var CharsetConverter
     */
    public $csConvObj;

    /**
     * @var int
     */
    public $sys_language_uid = 0;

    /**
     * @var string
     */
    public $sys_language_mode = '';

    /**
     * @var int
     */
    public $sys_language_content = 0;

    /**
     * @var int
     */
    public $sys_language_contentOL = 0;

    /**
     * @var int
     */
    public $ADMCMD_preview_BEUSER_uid = 0;

    /**
     * @var int
     */
    public $workspacePreview = 0;

    /**
     * @var bool
     */
    public $loginAllowedInBranch = false;

    /**
     * @var FrontendUserAuthentication
     */
    public $fe_user;

    /**
     * @var string
     */
    public $renderCharset = '';

    public function initTemplate(): void
    {
    }

    public function checkIfLoginAllowedInBranch(): bool
    {
        return false;
    }

    public function __construct()
    {
        //fake template object, otherwise tests cannot access this property
        $this->tmpl = new TemplateService();
        $this->sys_page = new PageRepository();
        $this->language = new SiteLanguage();
        $this->sys_language_isocode = 'ch';
        $this->csConvObj = new CharsetConverter();
        $this->cObj = new ContentObjectRenderer();
        $this->fe_user = new FrontendUserAuthentication();
    }

    public function applyHttpHeadersToResponse(): void
    {

    }

    public function processContentForOutput(): void
    {

    }

    public function processOutput(): void
    {

    }

    public function settingLocale(): void
    {

    }

    public function getLanguage(): SiteLanguage
    {
        return $this->language;
    }

    public function settingLanguage(): void
    {
    }

    public function getPageRenderer(): PageRenderer
    {
        return new PageRenderer();
    }

    public function getPageShortcut($SC, $mode, $thisUid, $itera = 20, $pageLog = [], $disableGroupCheck = false): array
    {
        return [];
    }

    public function csConv($str, $from = ''): void
    {
    }

    public function pageUnavailableAndExit($reason = '', $header = ''): void
    {
    }

    public function pageNotFoundAndExit($reason = '', $header = ''): void
    {
    }

    public function checkPageUnavailableHandler(): void
    {
    }

    public function pageUnavailableHandler($code, $header, $reason): void
    {
    }

    public function pageNotFoundHandler($code, $header = '', $reason = ''): void
    {
    }

    public function pageErrorHandler($code, $header = '', $reason = ''): void
    {
    }

    public function setContentType($contentType): void
    {
    }
}
