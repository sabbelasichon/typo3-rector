<?php
declare(strict_types=1);

namespace TYPO3\CMS\Backend\Template;

if (class_exists(DocumentTemplate::class)) {
    return;
}

final class DocumentTemplate
{
    public function xUaCompatible(string $content = 'IE=8'): void
    {

    }
}
