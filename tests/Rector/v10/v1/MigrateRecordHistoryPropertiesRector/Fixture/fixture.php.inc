<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\MigrateRecordHistoryPropertiesRector\Fixture;

use TYPO3\CMS\Core\History\RecordHistory;

$recordHistory = new RecordHistory();
$changeLog = $recordHistory->changeLog;
$lastEntry = $recordHistory->lastHistoryEntry;

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\MigrateRecordHistoryPropertiesRector\Fixture;

use TYPO3\CMS\Core\History\RecordHistory;

$recordHistory = new RecordHistory();
$changeLog = $recordHistory->getChangeLog();
$lastEntry = $recordHistory->getLastHistoryEntryNumber();

?>
