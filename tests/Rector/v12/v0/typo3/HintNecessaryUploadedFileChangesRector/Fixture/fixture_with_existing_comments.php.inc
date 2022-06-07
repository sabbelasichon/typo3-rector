<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HintNecessaryUploadedFileChangesRector\Fixture;

use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceStorage;

final class CustomResourceStorage extends ResourceStorage
{
    // FIXME: Rector: This comment must be removed
    public function addUploadedFile(array $uploadedFileData)
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HintNecessaryUploadedFileChangesRector\Fixture;

use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceStorage;

final class CustomResourceStorage extends ResourceStorage
{
    // FIXME: Rector: https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-97214-UseUploadedFileObjectsInsteadOf_FILES.html
    public function addUploadedFile(array $uploadedFileData)
    {
    }
}

?>
