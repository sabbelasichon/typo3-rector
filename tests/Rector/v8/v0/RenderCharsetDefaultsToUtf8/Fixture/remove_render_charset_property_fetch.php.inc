<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\RenderCharsetDefaultsToUtf8\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MaxWordCount
{
    /**
     * @var array
     */
    private $gp = [];

    /**
     * @var string
     */
    private $formFieldName = '';

    public function check()
    {
        mb_strlen(trim($this->gp[$this->formFieldName]), $GLOBALS['TSFE']->renderCharset) > 0;
        mb_strlen(trim($this->gp[$this->formFieldName]), $this->getTsfe()->renderCharset) > 0;
    }

    private function getTsfe(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\RenderCharsetDefaultsToUtf8\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MaxWordCount
{
    /**
     * @var array
     */
    private $gp = [];

    /**
     * @var string
     */
    private $formFieldName = '';

    public function check()
    {
        mb_strlen(trim($this->gp[$this->formFieldName]), 'utf-8') > 0;
        mb_strlen(trim($this->gp[$this->formFieldName]), 'utf-8') > 0;
    }

    private function getTsfe(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
