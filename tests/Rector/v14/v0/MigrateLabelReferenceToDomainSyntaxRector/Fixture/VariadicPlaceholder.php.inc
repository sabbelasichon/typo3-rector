<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateLabelReferenceToDomainSyntaxRector\Fixture;

use TYPO3\CMS\Core\Localization\LanguageService;

class VariadicPlaceholder
{
    public function keep(): void
    {
        $this->getTitle($this->getLanguageService()->sL(...));
    }

    public function getTitle(?callable $fn = null): string
    {
        return '';
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
