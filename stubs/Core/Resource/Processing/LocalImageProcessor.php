<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Resource\Processing;

if (class_exists(LocalImageProcessor::class)) {
    return;
}

final class LocalImageProcessor
{
    public function getTemporaryImageWithText(string $filename, string $textline1, string $textline2, string $textline3): string
    {
        return 'foo';
    }
}
