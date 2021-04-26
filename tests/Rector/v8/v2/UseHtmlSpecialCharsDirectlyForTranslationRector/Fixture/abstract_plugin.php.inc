<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v2\UseHtmlSpecialCharsDirectlyForTranslationRector\Fixture;

use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;

class MyPlugin extends AbstractPlugin
{
    public function init($hsc): void
    {
        $translation = $this->pi_getLL('label', '', true);
        $translation2 = $this->pi_getLL('label', '', false);
        $translation3 = $this->pi_getLL('label', '', $hsc);

        if($this->pi_getLL('label', '', true)) {
            $translation4 = $this->pi_getLL('label', '', true);
        }
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v2\UseHtmlSpecialCharsDirectlyForTranslationRector\Fixture;

use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;

class MyPlugin extends AbstractPlugin
{
    public function init($hsc): void
    {
        $translation = htmlspecialchars($this->pi_getLL('label', ''));
        $translation2 = $this->pi_getLL('label', '');
        $translation3 = $this->pi_getLL('label', '', $hsc);

        if(htmlspecialchars($this->pi_getLL('label', ''))) {
            $translation4 = htmlspecialchars($this->pi_getLL('label', ''));
        }
    }
}

?>
