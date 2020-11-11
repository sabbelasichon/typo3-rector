<?php
declare(strict_types=1);

namespace TYPO3\CMS\Backend\Template;

use TYPO3\CMS\Core\Page\PageRenderer;

if (class_exists(DocumentTemplate::class)) {
    return;
}

final class DocumentTemplate
{
    public function xUaCompatible(string $content = 'IE=8'): void
    {

    }

    public function getPageRenderer(): PageRenderer
    {
        return new PageRenderer();
    }

    public function addStyleSheet($key, $href, $title = '', $relation = 'stylesheet'): void
    {
    }

    public function wrapClickMenuOnIcon($content, $table, $uid = 0, $listFr = true, $addParams = '', $enDisItems = '', $returnTagParameters = false): void
    {

    }
}
