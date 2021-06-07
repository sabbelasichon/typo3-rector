<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\AdditionalFieldProviderRector\Fixture;

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class FileCleanupTaskAdditionalFields implements AdditionalFieldProviderInterface
{
    protected $fieldCleanupPath = 'cleanupPath';
    protected $fieldAgeInDays = 'ageInDays';

    public function getAdditionalFields (array &$taskInfo, $task, SchedulerModuleController $parentObject)
    {

        if (!isset($taskInfo[$this->fieldAgeInDays])) {
            if ($parentObject->CMD == 'edit') {
                $taskInfo[$this->fieldAgeInDays] = (int)$task->ageInDays;
            } else {
                $taskInfo[$this->fieldAgeInDays] = '';
            }
        }
        if (!isset($taskInfo[$this->fieldCleanupPath])) {
            if ($parentObject->CMD == 'edit') {
                $taskInfo[$this->fieldCleanupPath] = $task->cleanupPath;
            } else {
                $taskInfo[$this->fieldCleanupPath] = '';
            }
        }

        $additionalFields = array();
        $fieldCode = '<input type="number" name="tx_scheduler[' . $this->fieldAgeInDays . ']" id="' . $this->fieldAgeInDays . '" value="' . $taskInfo[$this->fieldAgeInDays] . '" size="10" placeholder="30 (days)" class="form-control" />';
        $additionalFields[$this->fieldAgeInDays] = array(
            'code'     => $fieldCode,
            'label'    => 'Minimum Age of files in days that should get deleted',
        );
        $fieldCode = '<input type="text" name="tx_scheduler[' . $this->fieldCleanupPath . ']" id="' . $this->fieldCleanupPath . '" value="' . $taskInfo[$this->fieldCleanupPath] . '" size="80" placeholder="/some/path/ (with trailing slash!)"  class="form-control" />';
        $additionalFields[$this->fieldCleanupPath] = array(
            'code'     => $fieldCode,
            'label'    => 'Path to be cleaned up (incl. trailing slash)',
        );

        return $additionalFields;
    }

    public function validateAdditionalFields (array &$submittedData, SchedulerModuleController $parentObject)
    {
        if ($submittedData[$this->fieldAgeInDays] <= 0) {
            $parentObject->addMessage('Please enter a age in days for files to be deleted.', FlashMessage::ERROR);
            return false;
        } elseif (!is_dir($submittedData[$this->fieldCleanupPath])) {
            $parentObject->addMessage('Please enter a valid full path where the cleanup should happen.', FlashMessage::ERROR);
            return false;
        }
        return true;
    }

    public function saveAdditionalFields (array $submittedData, AbstractTask $task)
    {
        $task->ageInDays = $submittedData[$this->fieldAgeInDays];
        $task->cleanupPath = $submittedData[$this->fieldCleanupPath];
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\AdditionalFieldProviderRector\Fixture;

use TYPO3\CMS\Scheduler\AbstractAdditionalFieldProvider;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class FileCleanupTaskAdditionalFields extends AbstractAdditionalFieldProvider
{
    protected $fieldCleanupPath = 'cleanupPath';
    protected $fieldAgeInDays = 'ageInDays';
    public function getAdditionalFields (array &$taskInfo, $task, SchedulerModuleController $parentObject)
    {

        if (!isset($taskInfo[$this->fieldAgeInDays])) {
            if ((string) $parentObject->getCurrentAction() == 'edit') {
                $taskInfo[$this->fieldAgeInDays] = (int)$task->ageInDays;
            } else {
                $taskInfo[$this->fieldAgeInDays] = '';
            }
        }
        if (!isset($taskInfo[$this->fieldCleanupPath])) {
            if ((string) $parentObject->getCurrentAction() == 'edit') {
                $taskInfo[$this->fieldCleanupPath] = $task->cleanupPath;
            } else {
                $taskInfo[$this->fieldCleanupPath] = '';
            }
        }

        $additionalFields = array();
        $fieldCode = '<input type="number" name="tx_scheduler[' . $this->fieldAgeInDays . ']" id="' . $this->fieldAgeInDays . '" value="' . $taskInfo[$this->fieldAgeInDays] . '" size="10" placeholder="30 (days)" class="form-control" />';
        $additionalFields[$this->fieldAgeInDays] = array(
            'code'     => $fieldCode,
            'label'    => 'Minimum Age of files in days that should get deleted',
        );
        $fieldCode = '<input type="text" name="tx_scheduler[' . $this->fieldCleanupPath . ']" id="' . $this->fieldCleanupPath . '" value="' . $taskInfo[$this->fieldCleanupPath] . '" size="80" placeholder="/some/path/ (with trailing slash!)"  class="form-control" />';
        $additionalFields[$this->fieldCleanupPath] = array(
            'code'     => $fieldCode,
            'label'    => 'Path to be cleaned up (incl. trailing slash)',
        );

        return $additionalFields;
    }
    public function validateAdditionalFields (array &$submittedData, SchedulerModuleController $parentObject)
    {
        if ($submittedData[$this->fieldAgeInDays] <= 0) {
            $this->addMessage('Please enter a age in days for files to be deleted.', FlashMessage::ERROR);
            return false;
        } elseif (!is_dir($submittedData[$this->fieldCleanupPath])) {
            $this->addMessage('Please enter a valid full path where the cleanup should happen.', FlashMessage::ERROR);
            return false;
        }
        return true;
    }
    public function saveAdditionalFields (array $submittedData, AbstractTask $task)
    {
        $task->ageInDays = $submittedData[$this->fieldAgeInDays];
        $task->cleanupPath = $submittedData[$this->fieldCleanupPath];
    }
}
?>
