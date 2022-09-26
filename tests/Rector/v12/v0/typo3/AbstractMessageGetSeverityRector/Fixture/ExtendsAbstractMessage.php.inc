<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\AbstractMessageGetSeverityRector\Fixture;

use \TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;

class MyMessage extends AbstractMessage
{
    public function getMessageSeverity(): ContextualFeedbackSeverity
    {
        return $this->getSeverity();
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\AbstractMessageGetSeverityRector\Fixture;

use \TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;

class MyMessage extends AbstractMessage
{
    public function getMessageSeverity(): ContextualFeedbackSeverity
    {
        return $this->getSeverity()->value;
    }
}
?>
