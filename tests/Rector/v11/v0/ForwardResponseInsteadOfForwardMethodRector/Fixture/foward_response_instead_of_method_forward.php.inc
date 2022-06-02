<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\ForwardResponseInsteadOfForwardMethodRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    private $forwardMe = false;

    public function copyAction(): void
    {
        $this->forward('new', null, null, ['foo' => 'bar']);
    }

    public function copy2Action()
    {
        if ($this->forwardMe) {
            $this->forward('new', null, null, ['foo' => 'bar']);
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

    public function copy2Action()
    {
        if ($this->forwardMe) {
            return (new ForwardResponse('new'))->withArguments(['foo' => 'bar']);
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
