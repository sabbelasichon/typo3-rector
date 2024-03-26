<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Filelist\Controller\File;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Resource\Exception\InsufficientFileAccessPermissionsException;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\ResourceInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Script Class for the replace-file form
 * @internal This class is a specific Backend controller implementation and is not considered part of the Public TYPO3 API.
 */
class ReplaceFileController
{
    /**
     * sys_file uid
     *
     * @var int
     */
    protected $uid;

    /**
     * The file or folder object that should be renamed
     *
     * @var ResourceInterface|null
     */
    protected $fileOrFolderObject;

    /**
     * Return URL of list module.
     *
     * @var string
     */
    protected $returnUrl;

    protected ModuleTemplate $view;

    public function __construct(
        protected readonly IconFactory $iconFactory,
        protected readonly PageRenderer $pageRenderer,
        protected readonly UriBuilder $uriBuilder,
        protected readonly ResourceFactory $resourceFactory,
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
    ) {}

    /**
     * Processes the request, currently everything is handled and put together via "main()"
     */
    public function mainAction(ServerRequestInterface $request): ResponseInterface
    {
        $this->view = $this->moduleTemplateFactory->create($request);
        $this->init($request);
        $this->renderContent();
        return $this->view->renderResponse('File/ReplaceFile');
    }

    /**
     * @throws \RuntimeException
     * @throws InsufficientFileAccessPermissionsException
     */
    protected function init(ServerRequestInterface $request): void
    {
        $parsedBody = $request->getParsedBody();
        $queryParams = $request->getQueryParams();
        $lang = $this->getLanguageService();

        // Initialize GPvars:
        $this->uid = (int)($parsedBody['uid'] ?? $queryParams['uid'] ?? 0);
        $this->returnUrl = GeneralUtility::sanitizeLocalUrl($parsedBody['returnUrl'] ?? $queryParams['returnUrl'] ?? '');

        // Cleaning and checking uid
        if ($this->uid > 0) {
            $this->fileOrFolderObject = $this->resourceFactory->retrieveFileOrFolderObject('file:' . $this->uid);
        }
        if (!$this->fileOrFolderObject) {
            $title = $lang->sL('LLL:EXT:filelist/Resources/Private/Language/locallang_mod_file_list.xlf:paramError');
            $message = $lang->sL('LLL:EXT:filelist/Resources/Private/Language/locallang_mod_file_list.xlf:targetNoDir');
            throw new \RuntimeException($title . ': ' . $message, 1436895930);
        }
        if ($this->fileOrFolderObject->getStorage()->getUid() === 0) {
            throw new InsufficientFileAccessPermissionsException(
                'You are not allowed to access files outside your storages',
                1436895931
            );
        }

        // If a folder should be renamed, AND the returnURL should go to the old directory name, the redirect is forced
        // so the redirect will NOT end in an error message
        // this case only happens if you select the folder itself in the foldertree and then use the clickmenu to
        // rename the folder
        if ($this->fileOrFolderObject instanceof Folder) {
            $parsedUrl = parse_url($this->returnUrl);
            $queryParts = GeneralUtility::explodeUrl2Array(urldecode($parsedUrl['query'] ?? ''));
            $queryPartsId = $queryParts['id'] ?? null;
            if ($queryPartsId === $this->fileOrFolderObject->getCombinedIdentifier()) {
                $this->returnUrl = str_replace(
                    urlencode($queryPartsId),
                    urlencode($this->fileOrFolderObject->getStorage()->getRootLevelFolder()->getCombinedIdentifier()),
                    $this->returnUrl
                );
            }
        }

        $this->view->getDocHeaderComponent()->setMetaInformationForResource($this->fileOrFolderObject);
        $this->pageRenderer->loadJavaScriptModule('@typo3/backend/context-menu.js');
        $this->pageRenderer->loadJavaScriptModule('@typo3/filelist/file-replace.js');
    }

    /**
     * Render module content
     */
    protected function renderContent(): void
    {
        // Assign variables used by the fluid template
        $assigns = [];
        $assigns['moduleUrlTceFile'] = (string)$this->uriBuilder->buildUriFromRoute('tce_file');
        $assigns['uid'] = $this->uid;
        $assigns['returnUrl'] = $this->returnUrl;

        $buttonBar = $this->view->getDocHeaderComponent()->getButtonBar();

        // Back button
        if ($this->returnUrl) {
            $returnButton = $buttonBar->makeLinkButton()
                ->setHref($this->returnUrl)
                ->setTitle($this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.goBack'))
                ->setShowLabelText(true)
                ->setIcon($this->iconFactory->getIcon('actions-view-go-back', Icon::SIZE_SMALL));
            $buttonBar->addButton($returnButton);
        }
        $this->view->assignMultiple($assigns);
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
