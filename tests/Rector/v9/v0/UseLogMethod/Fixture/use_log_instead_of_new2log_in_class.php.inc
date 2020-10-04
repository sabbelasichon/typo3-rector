<?php

use TYPO3\CMS\Core\DataHandling\DataHandler;

final class MyCustomHandler
{
    /**
     * @var DataHandler
     */
    private $dataHandler;

    public function __construct(DataHandler $dataHandler)
    {
        $this->dataHandler = $dataHandler;
    }

    public function logMe(): void
    {
        $logEntryUid1 = $this->dataHandler->newlog2('Foo', 'pages', 1, null, 0);
    }
}

?>
-----
<?php

use TYPO3\CMS\Core\DataHandling\DataHandler;

final class MyCustomHandler
{
    /**
     * @var DataHandler
     */
    private $dataHandler;

    public function __construct(DataHandler $dataHandler)
    {
        $this->dataHandler = $dataHandler;
    }

    public function logMe(): void
    {
        $propArr = $this->dataHandler->getRecordProperties('pages', 1);
        $pid = $propArr['pid'];

        $logEntryUid1 = $this->dataHandler->log('pages', 1, 0, 0, 0, 'Foo', -1, [], $this->dataHandler->eventPid('pages', 1, $pid));
    }
}

?>
