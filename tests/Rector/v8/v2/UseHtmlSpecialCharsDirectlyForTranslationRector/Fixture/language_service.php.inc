<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v2\UseHtmlSpecialCharsDirectlyForTranslationRector\Fixture;

use TYPO3\CMS\Lang\LanguageService;

class MyService
{
    public function init($hsc): void
    {
        $translation = $this->getLanguageService()->sL('foobar');
        $translation2 = $this->getLanguageService()->sL('foobar', true);
        $translation3 = $this->getLanguageService()->sL('foobar', $hsc);

        $translation4 = $this->getLanguageService()->getLLL('foobar', 'de', true);
        $translation5 = $this->getLanguageService()->getLLL('foobar', 'de', false);

        $translation6 = $this->getLanguageService()->getLL('foobar', true);
        $translation7 = $this->getLanguageService()->getLL('foobar', false);
        $translation8 = $this->getLanguageService()->sL('foobar', $hsc);

        $translation9 = $GLOBALS['LANG']->sL('foobar', true);
        $translation10 = $GLOBALS['LANG']->sL('foobar', false);

        $translation11 = $GLOBALS['LANG']->getLLL('foobar', '');
        $translation12 = $GLOBALS['LANG']->getLL('foobar', true);
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v2\UseHtmlSpecialCharsDirectlyForTranslationRector\Fixture;

use TYPO3\CMS\Lang\LanguageService;

class MyService
{
    public function init($hsc): void
    {
        $translation = $this->getLanguageService()->sL('foobar');
        $translation2 = htmlspecialchars($this->getLanguageService()->sL('foobar'));
        $translation3 = $this->getLanguageService()->sL('foobar', $hsc);

        $translation4 = htmlspecialchars($this->getLanguageService()->getLLL('foobar', 'de'));
        $translation5 = $this->getLanguageService()->getLLL('foobar', 'de');

        $translation6 = htmlspecialchars($this->getLanguageService()->getLL('foobar'));
        $translation7 = $this->getLanguageService()->getLL('foobar');
        $translation8 = $this->getLanguageService()->sL('foobar', $hsc);

        $translation9 = htmlspecialchars($GLOBALS['LANG']->sL('foobar'));
        $translation10 = $GLOBALS['LANG']->sL('foobar');

        $translation11 = $GLOBALS['LANG']->getLLL('foobar', '');
        $translation12 = htmlspecialchars($GLOBALS['LANG']->getLL('foobar'));
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}

?>
