<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateBooleanSortDirectionInFileListRector\Fixture;

use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Filelist\FileList;
use TYPO3\CMS\Filelist\Type\Mode;

class MyClass extends FileList
{
    protected Folder $selectedFolder;

    public function foo(): void
    {
        $this->start($this->selectedFolder, 1, 'name', false, Mode::MANAGE);
        $this->start($this->selectedFolder, 1, 'name', true, Mode::MANAGE);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateBooleanSortDirectionInFileListRector\Fixture;

use TYPO3\CMS\Filelist\Type\SortDirection;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Filelist\FileList;
use TYPO3\CMS\Filelist\Type\Mode;

class MyClass extends FileList
{
    protected Folder $selectedFolder;

    public function foo(): void
    {
        $this->start($this->selectedFolder, 1, 'name', SortDirection::ASCENDING, Mode::MANAGE);
        $this->start($this->selectedFolder, 1, 'name', SortDirection::DESCENDING, Mode::MANAGE);
    }
}

?>
