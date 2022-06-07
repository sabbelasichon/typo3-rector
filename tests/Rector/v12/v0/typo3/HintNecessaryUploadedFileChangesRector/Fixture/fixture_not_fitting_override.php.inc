<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HintNecessaryUploadedFileChangesRector\Fixture;

final class NotExtendingResourceStorage
{
    public function addUploadedFile(array $foo)
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\HintNecessaryUploadedFileChangesRector\Fixture;

final class NotExtendingResourceStorage
{
    public function addUploadedFile(array $foo)
    {
    }
}

?>
