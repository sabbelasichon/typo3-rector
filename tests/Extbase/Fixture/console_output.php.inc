<?php

use TYPO3\CMS\Extbase\Mvc\Cli\ConsoleOutput;
use TYPO3\CMS\Extbase\Mvc\Cli\ConsoleOutput;

final class TestCommandController
{
    /**
     * @var ConsoleOutput
     */
    private $output;

    /**
     * CommandController constructor.
     */
    public function __construct()
    {
        $objectManager = new ObjectManager();
        $this->output = $objectManager->get(ConsoleOutput::class);
    }

    /**
     * @throws Exception
     */
    public function someMethod(): void
    {
        $this->output->select('The question', [1, 2, 3], null, false, false);
        $this->output->select('The question', [1, 2, 3], null, false, 10);
        $this->output->askAndValidate('The question', static function () { return true; }, false);
        $this->output->askAndValidate('The question', static function () { return true; }, 10);
    }
}

?>
-----
<?php

use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManager;

final class TestCommandController
{
    /**
     * @var ConsoleOutput
     */
    private $output;

    /**
     * CommandController constructor.
     */
    public function __construct()
    {
        $objectManager = new ObjectManager();
        $this->output = $objectManager->get(ConsoleOutput::class);
    }

    /**
     * @throws Exception
     */
    public function someMethod(): void
    {
        $this->output->select('The question', [1, 2, 3], null, false, null);
        $this->output->select('The question', [1, 2, 3], null, false, 10);
        $this->output->askAndValidate('The question', static function () { return true; }, null);
        $this->output->askAndValidate('The question', static function () { return true; }, 10);
    }
}

?>
