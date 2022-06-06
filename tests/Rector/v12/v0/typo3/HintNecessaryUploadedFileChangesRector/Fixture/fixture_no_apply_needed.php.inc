<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HintNecessaryUploadedFileChangesRector\Fixture;

use TYPO3\CMS\Core\Http\UploadedFile;
use TYPO3\CMS\Core\Resource\ResourceStorage;

final class UpdatedResourceStorage extends ResourceStorage
{
    public function addUploadedFile(array|UploadedFile $uploadedFileData)
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HintNecessaryUploadedFileChangesRector\Fixture;

use TYPO3\CMS\Core\Http\UploadedFile;
use TYPO3\CMS\Core\Resource\ResourceStorage;

final class UpdatedResourceStorage extends ResourceStorage
{
    public function addUploadedFile(array|UploadedFile $uploadedFileData)
    {
    }
}

?>
