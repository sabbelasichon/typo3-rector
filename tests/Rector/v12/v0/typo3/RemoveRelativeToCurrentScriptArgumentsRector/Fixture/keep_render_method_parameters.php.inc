<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveRelativeToCurrentScriptArgumentsRector\Fixture;

use TYPO3\CMS\Core\Resource\FileInterface;

class MyFileRenderer {

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
