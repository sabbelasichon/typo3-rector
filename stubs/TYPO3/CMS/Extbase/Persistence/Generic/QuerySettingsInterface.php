<?php

namespace TYPO3\CMS\Extbase\Persistence\Generic;

use TYPO3\CMS\Core\Context\LanguageAspect;

if (interface_exists('TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface')) {
    return;
}

interface QuerySettingsInterface
{
    /**
     * @param bool $respectStoragePage If TRUE the storage page ID will be determined and the statement will be extended accordingly.
     * @return QuerySettingsInterface instance of $this to allow method chaining
     */
    public function setRespectStoragePage($respectStoragePage);

    /**
     * @param bool $respectSysLanguage TRUE if only record language should be respected when querying
     * @return QuerySettingsInterface instance of $this to allow method chaining
     */
    public function setRespectSysLanguage($respectSysLanguage);

    public function getLanguageAspect(): LanguageAspect;

    /**
     * @return $this to allow method chaining
     */
    public function setLanguageAspect(LanguageAspect $languageAspect);
}
