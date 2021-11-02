<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\ProvideCObjViaMethodRector\Fixture;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class Foo
{
    public $cObj;

    public function setContentObjectRenderer(ContentObjectRenderer $contentObjectRenderer): void
    {
        $this->cObj = $contentObjectRenderer;
    }
}

?>
