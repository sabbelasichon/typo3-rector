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

namespace TYPO3\CMS\Backend\Controller\Wizard;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Form\FormDataCompiler;
use TYPO3\CMS\Backend\Form\FormDataGroup\TcaDatabaseRecord;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Script Class for adding new items to a group/select field. Performs proper redirection as needed.
 * Script is typically called after new child record was added and then adds the new child to select value of parent.
 *
 * @internal This class is a specific Backend controller implementation and is not considered part of the Public TYPO3 API.
 */
class AddController
{
    /**
     * If set, the DataHandler class is loaded and used to add the returning ID to the parent record.
     */
    protected int $processDataFlag = 0;

    /**
     * Create new record -pid (pos/neg). If blank, return immediately
     */
    protected int $pid = 0;

    /**
     * The parent table we are working on.
     */
    protected string $table = '';

    /**
     * Loaded with the created id of a record FormEngine returns ...
     */
    protected int $id = 0;

    /**
     * Wizard parameters, coming from TCEforms linking to the wizard.
     */
    protected array $P = [];

    /**
     * Information coming back from the FormEngine script, telling what the table/id was of the newly created record.
     */
    protected string $returnEditConf = '';

    /**
     * Injects the request object for the current request or subrequest
     * As this controller goes only through the main() method, it is rather simple for now
     */
    public function mainAction(ServerRequestInterface $request): ResponseInterface
    {
        $this->init($request);

        if ($this->returnEditConf) {
            if ($this->processDataFlag) {
                // Because OnTheFly can't handle MM relations with intermediate tables we use TcaDatabaseRecord here
                // Otherwise already stored relations are overwritten with the new entry
                $formDataCompiler = GeneralUtility::makeInstance(FormDataCompiler::class);
                $input = [
                    'request' => $request,
                    'tableName' => $this->P['table'],
                    'vanillaUid' => (int)$this->P['uid'],
                    'command' => 'edit',
                ];
                $result = $formDataCompiler->compile($input, $formDataGroup = GeneralUtility::makeInstance(TcaDatabaseRecord::class));
                $currentParentRow = $result['databaseRow'];

                // If that record was found (should absolutely be...), then init DataHandler and set, prepend or append
                // the record
                if (is_array($currentParentRow)) {
                    $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
                    $data = [];
                    $recordId = $this->table . '_' . $this->id;
                    // Setting the new field data:
                    // If the field is a flexForm field, work with the XML structure instead:
                    if ($this->P['flexFormPath']) {
                        // Current value of flexForm path:
                        $currentFlexFormData = $currentParentRow[$this->P['field']];
                        $currentFlexFormValueByPath = ArrayUtility::getValueByPath($currentFlexFormData, $this->P['flexFormPath']);

                        // Compile currentFlexFormData to functional string
                        $currentFlexFormValues = [];
                        foreach ($currentFlexFormValueByPath as $value) {
                            if (is_array($value)) {
                                // group fields are always resolved to array
                                $currentFlexFormValues[] = $value['table'] . '_' . $value['uid'];
                            } else {
                                // but select fields may be uids only
                                $currentFlexFormValues[] = $value;
                            }
                        }
                        $currentFlexFormValue = implode(',', $currentFlexFormValues);

                        $insertValue = '';
                        switch ((string)$this->P['params']['setValue']) {
                            case 'set':
                                $insertValue = $recordId;
                                break;
                            case 'append':
                                $insertValue = $currentFlexFormValue . ',' . $recordId;
                                break;
                            case 'prepend':
                                $insertValue = $recordId . ',' . $currentFlexFormValue;
                                break;
                        }
                        $insertValue = implode(',', GeneralUtility::trimExplode(',', $insertValue, true));
                        $data[$this->P['table']][$this->P['uid']][$this->P['field']] = ArrayUtility::setValueByPath([], $this->P['flexFormPath'], $insertValue);
                    } else {
                        $currentValue = $currentParentRow[$this->P['field']];

                        // Normalize CSV values
                        if (!is_array($currentValue)) {
                            $currentValue = GeneralUtility::trimExplode(',', $currentValue, true);
                        }

                        // Normalize all items to "<table>_<uid>" format
                        $currentValue = array_map(function ($item) {
                            // Handle per-item table for "group" elements
                            if (is_array($item)) {
                                $item = $item['table'] . '_' . $item['uid'];
                            } else {
                                $item = $this->table . '_' . $item;
                            }

                            return $item;
                        }, $currentValue);

                        switch ((string)$this->P['params']['setValue']) {
                            case 'set':
                                $currentValue = [$recordId];
                                break;
                            case 'append':
                                $currentValue[] = $recordId;
                                break;
                            case 'prepend':
                                array_unshift($currentValue, $recordId);
                                break;
                        }

                        $data[$this->P['table']][$this->P['uid']][$this->P['field']] = implode(',', $currentValue);
                    }
                    // Submit the data:
                    $dataHandler->start($data, []);
                    $dataHandler->process_datamap();
                }
            }
            // Return to the parent FormEngine record editing session:
            return new RedirectResponse(GeneralUtility::sanitizeLocalUrl($this->P['returnUrl']));
        }

        // Redirecting to FormEngine with instructions to create a new record
        // AND when closing to return back with information about that records ID etc.
        $normalizedParams = $request->getAttribute('normalizedParams');
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $redirectUrl = (string)$uriBuilder->buildUriFromRoute('record_edit', [
            'returnEditConf' => 1,
            'edit[' . $this->P['params']['table'] . '][' . $this->pid . ']' => 'new',
            'returnUrl' => $normalizedParams->getRequestUri(),
        ]);

        return new RedirectResponse($redirectUrl);
    }

    /**
     * Initialization of the class.
     */
    protected function init(ServerRequestInterface $request): void
    {
        $parsedBody = $request->getParsedBody();
        $queryParams = $request->getQueryParams();
        // Init GPvars:
        $this->P = $parsedBody['P'] ?? $queryParams['P'] ?? [];
        $this->returnEditConf = $parsedBody['returnEditConf'] ?? $queryParams['returnEditConf'] ?? '';
        // Get this record
        $record = BackendUtility::getRecord($this->P['table'], $this->P['uid']);
        // Set table:
        $this->table = $this->P['params']['table'];
        // Get TSconfig for it.
        $TSconfig = BackendUtility::getTCEFORM_TSconfig(
            $this->P['table'],
            is_array($record) ? $record : ['pid' => (int)$this->P['params']['pid']]
        );
        // Set [params][pid]
        if (str_starts_with($this->P['params']['pid'], '###') && str_ends_with($this->P['params']['pid'], '###')) {
            $keyword = substr($this->P['params']['pid'], 3, -3);
            $this->pid = str_starts_with($keyword, 'PAGE_TSCONFIG_')
                ? (int)$TSconfig[$this->P['field']][$keyword]
                : (int)$TSconfig['_' . $keyword];
        } else {
            $this->pid = (int)$this->P['params']['pid'];
        }

        // If a new id has returned from a newly created record...
        if ($this->returnEditConf) {
            $editConfiguration = json_decode($this->returnEditConf, true);
            if (is_array($editConfiguration[$this->table]) && MathUtility::canBeInterpretedAsInteger($this->P['uid'])) {
                // Getting id and cmd from returning editConf array.
                reset($editConfiguration[$this->table]);
                $this->id = (int)key($editConfiguration[$this->table]);
                $cmd = current($editConfiguration[$this->table]);
                // ... and if everything seems OK we will register some classes for inclusion and instruct the object
                // to perform processing later.
                if ($this->P['params']['setValue']
                    && $cmd === 'edit'
                    && $this->id
                    && $this->P['table']
                    && $this->P['field'] && $this->P['uid']
                ) {
                    $liveRecord = BackendUtility::getLiveVersionOfRecord($this->table, $this->id, 'uid');
                    if ($liveRecord) {
                        $this->id = $liveRecord['uid'];
                    }
                    $this->processDataFlag = 1;
                }
            }
        }
    }
}
