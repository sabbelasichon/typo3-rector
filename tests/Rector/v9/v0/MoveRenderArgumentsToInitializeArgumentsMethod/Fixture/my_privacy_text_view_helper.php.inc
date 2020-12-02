<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\ViewHelpers\Fixture;


use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class MyPrivacyViewHelper extends AbstractViewHelper
{
    /**
     * Renders the terms link.
     *
     * @param int $termsLink
     * @param int $privacyPolicyLink
     * @param string $class
     *
     * @return string the terms link.
     */
    public function render($termsLink = 0, $privacyPolicyLink = 0, $class = 'fancyboxPage')
    {
        $termsHref = ($termsLink > 0) ? $GLOBALS['TSFE']->cObj->typoLink_URL(array('parameter' => $termsLink)) : '';
        $privacyPolicyHref = ($termsLink > 0) ? $GLOBALS['TSFE']->cObj->typoLink_URL(array('parameter' => $privacyPolicyLink)) : '';
        $termsText = '';
        if ($termsHref !== '' || $privacyPolicyHref !== '') {
            if ($termsHref !== '' && $privacyPolicyHref !== '') {
                $termsTag = '<a href="'.$termsHref.'" class="'.$class.'">'.$this->translate('termsAndConditions').'</a>';
                $privacyPolicyTag = '<a href="'.$privacyPolicyHref.'" class="'.$class.'">'.$this->translate('privacyPolicy').'</a>';
                $termsText = sprintf($this->translate('termsTextComplete'), $termsTag, $privacyPolicyTag);
            } elseif ($termsHref !== '') {
                $termsTag = '<a href="'.$termsHref.'" class="'.$class.'">'.$this->translate('termsAndConditions').'</a>';
                $termsText = sprintf($this->translate('termsTextOnlyTerms'), $termsTag);
            } else {
                $privacyPolicyTag = '<a href="'.$privacyPolicyHref.'" class="'.$class.'">'.$this->translate('privacyPolicy').'</a>';
                $termsText = sprintf($this->translate('termsTextOnlyPrivacy'), $privacyPolicyTag);
            }
        }

        return $termsText;
    }

    /**
     * Translator helper
     *
     * @param string $key Locallang key
     *
     * @return string translation for $key
     */
    protected function translate($key)
    {
        return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($key, 'ppw_si_programs');
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\ViewHelpers\Fixture;


use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class MyPrivacyViewHelper extends AbstractViewHelper
{
    /**
     * Renders the terms link.
     *
     *
     * @return string the terms link.
     */
    public function render()
    {
        $termsLink = $this->arguments['termsLink'];
        $privacyPolicyLink = $this->arguments['privacyPolicyLink'];
        $class = $this->arguments['class'];
        $termsHref = ($termsLink > 0) ? $GLOBALS['TSFE']->cObj->typoLink_URL(array('parameter' => $termsLink)) : '';
        $privacyPolicyHref = ($termsLink > 0) ? $GLOBALS['TSFE']->cObj->typoLink_URL(array('parameter' => $privacyPolicyLink)) : '';
        $termsText = '';
        if ($termsHref !== '' || $privacyPolicyHref !== '') {
            if ($termsHref !== '' && $privacyPolicyHref !== '') {
                $termsTag = '<a href="'.$termsHref.'" class="'.$class.'">'.$this->translate('termsAndConditions').'</a>';
                $privacyPolicyTag = '<a href="'.$privacyPolicyHref.'" class="'.$class.'">'.$this->translate('privacyPolicy').'</a>';
                $termsText = sprintf($this->translate('termsTextComplete'), $termsTag, $privacyPolicyTag);
            } elseif ($termsHref !== '') {
                $termsTag = '<a href="'.$termsHref.'" class="'.$class.'">'.$this->translate('termsAndConditions').'</a>';
                $termsText = sprintf($this->translate('termsTextOnlyTerms'), $termsTag);
            } else {
                $privacyPolicyTag = '<a href="'.$privacyPolicyHref.'" class="'.$class.'">'.$this->translate('privacyPolicy').'</a>';
                $termsText = sprintf($this->translate('termsTextOnlyPrivacy'), $privacyPolicyTag);
            }
        }
        return $termsText;
    }

    /**
     * Translator helper
     *
     * @param string $key Locallang key
     *
     * @return string translation for $key
     */
    protected function translate($key)
    {
        return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($key, 'ppw_si_programs');
    }

    public function initializeArguments(): void
    {
        $this->registerArgument('termsLink', 'int', '', false, 0);
        $this->registerArgument('privacyPolicyLink', 'int', '', false, 0);
        $this->registerArgument('class', 'string', '', false, 'fancyboxPage');
    }
}

?>
