<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Html;

if (class_exists('TYPO3\CMS\Core\Html\RteHtmlParser')) {
    return;
}


class RteHtmlParser
{
    public function  HTMLcleaner_db($content): string
    {
        return '';
    }

    public function getKeepTags($direction = 'rte'): array
    {
        return [];
    }

    public function getUrl($url): string
    {
        return '';
    }

    public function siteUrl(): string
    {
        return '';
    }
}
