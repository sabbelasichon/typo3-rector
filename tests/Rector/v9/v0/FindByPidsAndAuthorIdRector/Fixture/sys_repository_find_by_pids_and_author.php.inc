<?php

use TYPO3\CMS\Extbase\Domain\Model\BackendUser;
use TYPO3\CMS\SysNote\Domain\Repository\SysNoteRepository;

class MyNoteController
{
    /**
     * @var SysNoteRepository
     */
    private $sysNoteRepository;

    public function __construct(SysNoteRepository $sysNoteRepository)
    {
        $this->sysNoteRepository = $sysNoteRepository;
    }

    public function myMethod()
    {
        $backendUser = new BackendUser();
        $this->sysNoteRepository->findByPidsAndAuthor('1,2,3', $backendUser);
        $this->sysNoteRepository->findByPidsAndAuthorId('1,2,3', $backendUser->getUid());
    }
}

?>
-----
<?php

use TYPO3\CMS\Extbase\Domain\Model\BackendUser;
use TYPO3\CMS\SysNote\Domain\Repository\SysNoteRepository;

class MyNoteController
{
    /**
     * @var SysNoteRepository
     */
    private $sysNoteRepository;

    public function __construct(SysNoteRepository $sysNoteRepository)
    {
        $this->sysNoteRepository = $sysNoteRepository;
    }

    public function myMethod()
    {
        $backendUser = new BackendUser();
        $this->sysNoteRepository->findByPidsAndAuthorId('1,2,3', $backendUser->getUid());
        $this->sysNoteRepository->findByPidsAndAuthorId('1,2,3', $backendUser->getUid());
    }
}

?>
