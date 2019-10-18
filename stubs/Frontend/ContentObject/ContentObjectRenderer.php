<?php

declare(strict_types=1);

namespace TYPO3\CMS\Frontend\ContentObject;

final class ContentObjectRenderer
{
    public function RECORDS(array $config): void
    {
        $this->cObjGetSingle('RECORDS', $config);
    }

    public function cObjGetSingle(string $string, array $config): void
    {
    }
}
