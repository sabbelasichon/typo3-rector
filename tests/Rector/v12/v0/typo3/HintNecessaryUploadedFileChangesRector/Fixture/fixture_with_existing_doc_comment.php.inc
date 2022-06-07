<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HintNecessaryUploadedFileChangesRector\Fixture;

use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceStorage;

final class CustomResourceStorage extends ResourceStorage
{
    /**
     * Adds an uploaded file into the Storage. Previously in \TYPO3\CMS\Core\Utility\File\ExtendedFileUtility::file_upload()
     *
     * @param array $uploadedFileData contains information about the uploaded file given by $_FILES['file1']
     */
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
    /**
     * Adds an uploaded file into the Storage. Previously in \TYPO3\CMS\Core\Utility\File\ExtendedFileUtility::file_upload()
     *
     * @param array $uploadedFileData contains information about the uploaded file given by $_FILES['file1']
     */
    // FIXME: Rector: https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-97214-UseUploadedFileObjectsInsteadOf_FILES.html
    public function addUploadedFile(array $uploadedFileData)
    {
    }
}

?>
