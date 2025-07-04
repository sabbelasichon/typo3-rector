<?php
namespace TYPO3\CMS\Extbase\Persistence\Generic;

use TYPO3\CMS\Core\Context\LanguageAspect;

if (class_exists('TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings')) {
    return;
}

class Typo3QuerySettings implements QuerySettingsInterface
{
    /**
     * @var int
     */
    private $languageUid = 0;

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings
     */
    public function setLanguageMode()
    {
        return $this;
    }

    public function getLanguageMode()
    {
        return null;
    }

    /**
     * @param int $languageUid
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings
     */
    public function setLanguageUid($languageUid)
    {
        $languageUid = (int) $languageUid;
        $this->languageUid = $languageUid;
        return $this;
    }

    /**
     * @return int
     */
    public function getLanguageUid()
    {
        return $this->languageUid;
    }

    public function setRespectStoragePage($respectStoragePage)
    {
    }

    public function setRespectSysLanguage($respectSysLanguage)
    {
    }

    /**
     * @param mixed $languageOverlayMode TRUE, FALSE or "hideNonTranslated"
     * @return QuerySettingsInterface instance of $this to allow method chaining
     */
    public function setLanguageOverlayMode($languageOverlayMode = false)
    {
        return $this;
    }

    public function getLanguageAspect(): LanguageAspect
    {
    }

    public function setLanguageAspect(LanguageAspect $languageAspect)
    {
        return $this;
    }
}
