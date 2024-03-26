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

namespace TYPO3\CMS\Backend\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Attribute\Controller;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Handle FormEngine AJAX calls for Slug validation and sanitization
 *
 * @internal This class is a specific Backend controller implementation and is not considered part of the Public TYPO3 API.
 */
#[Controller]
class FormSlugAjaxController extends AbstractFormEngineAjaxController
{
    public function __construct(
        private Context $context
    ) {}

    /**
     * Validates a given slug against the site and give a suggestion when it's already in use
     *
     * For new records this will look like this:
     * - If "slug" field is empty, take the other fields, and generate the slug based on the sent fields.
     *      - JS: adapt the "placeholder" value only, as on save the field will be filled with the value via DataHandler
     * - If "slug" field is not empty (= "unlocked" and manually typed in)
     *  - sanitize the slug
     *      - If 'uniqueInSite' is set check if it's unique for the site
     *        - If not unique propose another slug and return this with the flag hasConflicts = true
     *      - If 'uniqueInPid' is set check if it's unique for the pid
     *        - If not unique propose another slug and return this with the flag hasConflicts = true
     *
     * For existing records:
     *  - sanitize the slug
     *      - If 'uniqueInSite' is set check if it's unique for the site
     *        - If not unique propose another slug and return this with the flag hasConflicts = true
     *      - If 'uniqueInPid' is set check if it's unique for the pid
     *        - If not unique propose another slug and return this with the flag hasConflicts = true
     *      - If the slug has changed from the existing database record (@todo)
     *          - Show a message that the old URL will stop working (possibly add a redirect via checkbox)
     *          - If the page has subpages, show a warning that the subpages WILL NOT BE MODIFIED and keep the OLD url
     *
     * @param ServerRequestInterface $request
     * @throws \RuntimeException
     */
    public function suggestAction(ServerRequestInterface $request): ResponseInterface
    {
        $this->checkRequest($request);

        $queryParameters = $request->getParsedBody() ?? [];
        $values = $queryParameters['values'];
        $mode = $queryParameters['mode'];
        $tableName = (string)($queryParameters['tableName'] ?? '');
        $pid = (int)$queryParameters['pageId'];
        $parentPageId = (int)$queryParameters['parentPageId'];
        $recordId = (int)$queryParameters['recordId'];
        $languageId = (int)$queryParameters['language'];
        $fieldName = $queryParameters['fieldName'];

        $fieldConfig = $GLOBALS['TCA'][$tableName]['columns'][$fieldName]['config'] ?? [];
        $row = (array)BackendUtility::getRecord($tableName, $recordId);
        $recordType = BackendUtility::getTCAtypeValue($tableName, $row);
        $columnsOverridesConfigOfField = $GLOBALS['TCA'][$tableName]['types'][$recordType]['columnsOverrides'][$fieldName]['config'] ?? null;
        if ($columnsOverridesConfigOfField) {
            ArrayUtility::mergeRecursiveWithOverrule($fieldConfig, $columnsOverridesConfigOfField);
        }
        if (empty($fieldConfig)) {
            throw new \RuntimeException(
                'No valid field configuration for table ' . $tableName . ' field name ' . $fieldName . ' found.',
                1535379534
            );
        }

        $evalInfo = !empty($fieldConfig['eval']) ? GeneralUtility::trimExplode(',', $fieldConfig['eval'], true) : [];
        $hasToBeUniqueInDb = in_array('unique', $evalInfo, true);
        $hasToBeUniqueInSite = in_array('uniqueInSite', $evalInfo, true);
        $hasToBeUniqueInPid = in_array('uniqueInPid', $evalInfo, true);

        $hasConflict = false;

        $recordData = $values;
        if (!isset($recordData['uid'])) {
            $recordData['uid'] = $recordId;
        }
        $recordData['pid'] = $pid;
        if (!empty($GLOBALS['TCA'][$tableName]['ctrl']['languageField'])) {
            $recordData[$GLOBALS['TCA'][$tableName]['ctrl']['languageField']] = $languageId;
        }
        if ($tableName === 'pages' && empty($recordData['is_siteroot'])) {
            $recordData['is_siteroot'] = $row['is_siteroot'] ?? false;
        }

        $workspaceId = $this->context->getPropertyFromAspect('workspace', 'id');
        $slug = GeneralUtility::makeInstance(SlugHelper::class, $tableName, $fieldName, $fieldConfig, $workspaceId);
        if ($mode === 'auto') {
            // New page - Feed incoming values to generator
            $proposal = $slug->generate($recordData, $pid);
        } elseif ($mode === 'recreate') {
            $proposal = $slug->generate($recordData, $parentPageId);
        } elseif ($mode === 'manual') {
            // Existing record - Fetch full record and only validate against the new "slug" field.
            $proposal = $slug->sanitize($values['manual']);
        } else {
            throw new \RuntimeException('mode must be either "auto", "recreate" or "manual"', 1535835666);
        }

        $state = RecordStateFactory::forName($tableName)
            ->fromArray($recordData, $pid, $recordId);
        if ($hasToBeUniqueInDb && !$slug->isUniqueInTable($proposal, $state)) {
            $hasConflict = true;
            $proposal = $slug->buildSlugForUniqueInTable($proposal, $state);
        }
        if ($hasToBeUniqueInSite && !$slug->isUniqueInSite($proposal, $state)) {
            $hasConflict = true;
            $proposal = $slug->buildSlugForUniqueInSite($proposal, $state);
        }
        if ($hasToBeUniqueInPid && !$slug->isUniqueInPid($proposal, $state)) {
            $hasConflict = true;
            $proposal = $slug->buildSlugForUniqueInPid($proposal, $state);
        }

        return new JsonResponse([
            'hasConflicts' => $hasConflict,
            'manual' => $values['manual'] ?? '',
            'proposal' => $proposal,
        ]);
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function checkRequest(ServerRequestInterface $request): bool
    {
        $queryParameters = $request->getParsedBody() ?? [];
        $expectedHash = GeneralUtility::hmac(
            implode(
                '',
                [
                    $queryParameters['tableName'],
                    $queryParameters['pageId'],
                    $queryParameters['recordId'],
                    $queryParameters['language'],
                    $queryParameters['fieldName'],
                    $queryParameters['command'],
                    $queryParameters['parentPageId'],
                ]
            ),
            __CLASS__
        );
        if (!hash_equals($expectedHash, $queryParameters['signature'])) {
            throw new \InvalidArgumentException(
                'HMAC could not be verified',
                1535137045
            );
        }
        return true;
    }
}
