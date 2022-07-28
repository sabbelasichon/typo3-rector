<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveRelativeToCurrentScriptArgumentsRector\Fixture;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface;

class MyFileRenderer implements FileRendererInterface {

    public function render(
        FileInterface $file,
        $width,
        $height,
        array $options = [],
        $usedPathsRelativeToCurrentScript = false
    ) {
        return '';
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveRelativeToCurrentScriptArgumentsRector\Fixture;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface;

class MyFileRenderer implements FileRendererInterface {

    public function render(
        FileInterface $file,
        $width,
        $height,
        array $options = []
    ) {
        return '';
    }
}

?>
