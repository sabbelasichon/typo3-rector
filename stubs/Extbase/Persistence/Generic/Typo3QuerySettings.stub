<?php
declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Persistence\Generic;

if (class_exists(Typo3QuerySettings::class)) {
    return;
}

final class Typo3QuerySettings
{
    /**
     * @var int
     */
    private $languageUid = 0;

    public function setLanguageMode(): Typo3QuerySettings
    {
        return $this;
    }

    public function getLanguageMode()
    {
        return null;
    }

    public function setLanguageUid(int $languageUid): Typo3QuerySettings
    {
        $this->languageUid = $languageUid;
        return $this;
    }

    public function getLanguageUid(): int
    {
        return $this->languageUid;
    }
}
