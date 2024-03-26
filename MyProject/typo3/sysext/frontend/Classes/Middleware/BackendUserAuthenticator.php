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

namespace TYPO3\CMS\Frontend\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Backend\FrontendBackendUserAuthentication;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Authentication\Mfa\MfaRequiredException;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Http\NormalizedParams;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This middleware authenticates a Backend User (be_user) (pre)-viewing a frontend page.
 *
 * This middleware also ensures that $GLOBALS['LANG'] is available, however it is possible that
 * a different middleware later-on might unset the BE_USER as he/she is not allowed to preview a certain
 * page due to rights management. As this can only happen once the page ID is resolved, this will happen
 * after the routing middleware.
 */
class BackendUserAuthenticator extends \TYPO3\CMS\Core\Middleware\BackendUserAuthenticator
{
    public function __construct(
        Context $context,
        protected readonly LanguageServiceFactory $languageServiceFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Creates a backend user authentication object, tries to authenticate a user
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Initializing a possible logged-in Backend User
        // If the backend cookie is set,
        // we proceed and check if a backend user is logged in.
        $backendUserObject = null;
        if (isset($request->getCookieParams()[BackendUserAuthentication::getCookieName()])) {
            $backendUserObject = $this->initializeBackendUser($request);
        }
        $GLOBALS['BE_USER'] = $backendUserObject;
        // Load specific dependencies which are necessary for a valid Backend User
        // like $GLOBALS['LANG'] for labels in the language of the BE User, the router, and ext_tables.php for all modules
        // So things like Frontend Editing and Admin Panel can use this for generating links to the TYPO3 Backend.
        if ($backendUserObject !== null) {
            $GLOBALS['LANG'] = $this->languageServiceFactory->createFromUserPreferences($GLOBALS['BE_USER']);
            Bootstrap::loadExtTables();
            $this->setBackendUserAspect($GLOBALS['BE_USER']);
            if ($this->context->getPropertyFromAspect('backend.user', 'isLoggedIn', false)
                && (strtolower($request->getServerParams()['HTTP_CACHE_CONTROL'] ?? '') === 'no-cache'
                    || strtolower($request->getServerParams()['HTTP_PRAGMA'] ?? '') === 'no-cache')
            ) {
                // Detecting if shift-reload has been clicked to set noCache attribute if so.
                // This is only done if a backend user is logged in to prevent DoS-attacks for "casual" requests.
                $request = $request->withAttribute('noCache', true);
            }
        }

        $response = $handler->handle($request);

        // If, when building the response, the user is still available, then ensure that the headers are sent properly
        if ($this->context->getAspect('backend.user')->isLoggedIn()) {
            return $this->applyHeadersToResponse($response);
        }
        return $response;
    }

    /**
     * Creates the backend user object and returns it if a valid backend user is found.
     */
    protected function initializeBackendUser(ServerRequestInterface $request): ?FrontendBackendUserAuthentication
    {
        // New backend user object
        $backendUserObject = GeneralUtility::makeInstance(FrontendBackendUserAuthentication::class);
        try {
            $backendUserObject->start($request);
        } catch (MfaRequiredException $e) {
            // Do nothing, as the user is not fully authenticated - has not
            // passed required multi-factor authentication - via the backend.
            return null;
        }
        if (!empty($backendUserObject->user['uid'])) {
            $this->setBackendUserAspect($backendUserObject, (int)$backendUserObject->user['workspace_id']);
            $backendUserObject->fetchGroupData();
        }
        // Unset the user initialization if any setting / restriction applies
        if (!$this->isAuthenticated($backendUserObject, $request, $request->getAttribute('normalizedParams'))) {
            $backendUserObject = null;
            $this->setBackendUserAspect(null);
        }
        return $backendUserObject;
    }

    /**
     * Implementing the access checks that the TYPO3 CMS bootstrap script does before a user is ever logged in.
     * Returns TRUE if access is OK
     */
    protected function isAuthenticated(FrontendBackendUserAuthentication $user, ServerRequestInterface $request, NormalizedParams $normalizedParams): bool
    {
        // Check IP
        $ipMask = trim($GLOBALS['TYPO3_CONF_VARS']['BE']['IPmaskList'] ?? '');
        if ($ipMask && !GeneralUtility::cmpIP($normalizedParams->getRemoteAddress(), $ipMask)) {
            return false;
        }
        // Check SSL (https)
        if ((bool)$GLOBALS['TYPO3_CONF_VARS']['BE']['lockSSL'] && !$normalizedParams->isHttps()) {
            return false;
        }
        return $user->backendCheckLogin($request);
    }
}
