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

namespace TYPO3\CMS\Backend\History\Event;

use TYPO3\CMS\Backend\History\RecordHistoryRollback;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

/**
 * This event is fired after a history record rollback finished.
 */
final class AfterHistoryRollbackFinishedEvent
{
    public function __construct(
        private readonly string $rollbackFields,
        private readonly array $diff,
        private readonly array $dataHandlerInput,
        private readonly RecordHistoryRollback $recordHistoryRollback,
        private readonly ?BackendUserAuthentication $backendUserAuthentication = null
    ) {}

    public function getRecordHistoryRollback(): RecordHistoryRollback
    {
        return $this->recordHistoryRollback;
    }

    public function getRollbackFields(): string
    {
        return $this->rollbackFields;
    }

    public function getDiff(): array
    {
        return $this->diff;
    }

    public function getDataHandlerInput(): array
    {
        return $this->dataHandlerInput;
    }

    public function getBackendUserAuthentication(): ?BackendUserAuthentication
    {
        return $this->backendUserAuthentication;
    }
}
