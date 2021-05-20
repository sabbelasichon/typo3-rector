<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Site\Entity;

if (class_exists('TYPO3\CMS\Core\Site\Entity\SiteLanguage')) {
    return;
}

class SiteLanguage
{
    public function getTwoLetterIsoCode(): string
    {
        return 'ch';
    }
}
