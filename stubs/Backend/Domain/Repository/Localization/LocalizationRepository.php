<?php

declare(strict_types=1);

namespace TYPO3\CMS\Backend\Domain\Repository\Localization;

if (class_exists(LocalizationRepository::class)) {
    return;
}

final class LocalizationRepository
{
    public function fetchOriginLanguage(int $pageId, int $localizedLanguage): array
    {
        return [];
    }

    public function getLocalizedRecordCount(int $pageId, int $languageId): int
    {
        return 1;
    }

    public function fetchAvailableLanguages(int $pageId, int $languageId): array
    {
        return [];
    }

    public function getRecordsToCopyDatabaseResult(int $pageId, int $destLanguageId, int $languageId, string $fields = '*'): void
    {
    }

    public function getUsedLanguagesInPage(): void
    {
    }

    public function getUsedLanguagesInPageAndColumn(): void
    {
    }
}
