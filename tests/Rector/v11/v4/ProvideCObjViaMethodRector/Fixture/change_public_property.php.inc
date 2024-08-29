<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\ProvideCObjViaMethodRector\Fixture;

class Foo
{
    public $cObj;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v4\ProvideCObjViaMethodRector\Fixture;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class Foo
{
    protected $cObj;

    public function setContentObjectRenderer(ContentObjectRenderer $cObj): void
    {
        $this->cObj = $cObj;
    }
}

?>
