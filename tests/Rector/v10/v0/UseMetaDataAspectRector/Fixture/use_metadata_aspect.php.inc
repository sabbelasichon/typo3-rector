<?php

use TYPO3\CMS\Core\Resource\File;

$fileObject = new File();
$fileObject->_getMetaData();


class MyEntity
{
    /**
     * @var File
     */
    private $file;

    public function myMethod(): void
    {
        $this->file->_getMetaData();
    }
}

?>
-----
<?php

use TYPO3\CMS\Core\Resource\File;

$fileObject = new File();
$fileObject->getMetaData()->get();


class MyEntity
{
    /**
     * @var File
     */
    private $file;

    public function myMethod(): void
    {
        $this->file->getMetaData()->get();
    }
}

?>
