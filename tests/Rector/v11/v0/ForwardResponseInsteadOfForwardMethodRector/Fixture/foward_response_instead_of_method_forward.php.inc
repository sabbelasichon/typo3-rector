<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\ForwardResponseInsteadOfForwardMethodRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    private $forwardMe = false;

    public function copyAction(): void
    {
        $this->forward('new', null, null, ['foo' => 'bar']);
    }

    /**
     * @return void
     */
    public function copyActionWithReturnDoc(): void
    {
        $this->forward('new', null, null, ['foo' => 'bar']);
    }

    /**
     * @param array $test
     * @return void
     */
    public function copyActionWithParamsAndMultipleStmts(array $test): void
    {
        $start = 1;
        $end = 2;
        if ($start > $end) {
            $this->forward('new', null, null, ['foo' => $test]);
        }
    }

    public function copy2Action()
    {
        if ($this->forwardMe) {
            try {
                $this->forward('new', null, null, ['foo' => 'bar']);
            } catch (\Exception $e) {

            }
        }

        return 'foo';
    }

    public function listAction()
    {
        $this->forwardToDetailActionWhenRequested();
    }

    protected function forwardToDetailActionWhenRequested()
    {
        if (empty($this->settings['link']['skipControllerAndAction']) || !$this->isActionAllowed('detail')) {
            return;
        }

        $this->forward('detail', null, null, ['foo' => 'bar']);
    }

    protected function isActionAllowed(string $action): bool
    {
        return true;
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\ForwardResponseInsteadOfForwardMethodRector\Fixture;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    private $forwardMe = false;

    public function copyAction(): ResponseInterface
    {
        return (new ForwardResponse('new'))->withArguments(['foo' => 'bar']);
    }

    /**
     *
     */
    public function copyActionWithReturnDoc(): ResponseInterface
    {
        return (new ForwardResponse('new'))->withArguments(['foo' => 'bar']);
    }

    /**
     * @param array $test
     *
     */
    public function copyActionWithParamsAndMultipleStmts(array $test)
    {
        $start = 1;
        $end = 2;
        if ($start > $end) {
            return (new ForwardResponse('new'))->withArguments(['foo' => $test]);
        }
    }

    public function copy2Action()
    {
        if ($this->forwardMe) {
            try {
                return (new ForwardResponse('new'))->withArguments(['foo' => 'bar']);
            } catch (\Exception $e) {

            }
        }

        return 'foo';
    }

    public function listAction()
    {
        $this->forwardToDetailActionWhenRequested();
    }

    protected function forwardToDetailActionWhenRequested()
    {
        if (empty($this->settings['link']['skipControllerAndAction']) || !$this->isActionAllowed('detail')) {
            return;
        }

        return (new ForwardResponse('detail'))->withArguments(['foo' => 'bar']);
    }

    protected function isActionAllowed(string $action): bool
    {
        return true;
    }
}

?>
