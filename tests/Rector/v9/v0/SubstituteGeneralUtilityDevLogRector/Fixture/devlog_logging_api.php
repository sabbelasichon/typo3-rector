<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\SubstituteGeneralUtilityDevLogRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

$data = ['data'];
GeneralUtility::devLog('message', 'foo', 0, $data);
GeneralUtility::devLog('message', 'foo', 1, $data);
GeneralUtility::devLog('message', 'foo', 2, $data);
GeneralUtility::devLog('message', 'foo', GeneralUtility::SYSLOG_SEVERITY_ERROR, $data);
GeneralUtility::devLog('message', 'foo', 4, $data);
