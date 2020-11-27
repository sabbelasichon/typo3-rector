<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Imaging;

if (class_exists(GraphicalFunctions::class)) {
    return;
}

final class GraphicalFunctions
{
    /**
     * @var string
     */
    public $tempPath = 'typo3temp/';

    public function prependAbsolutePath($fontFile): void
    {

    }

    public function getTemporaryImageWithText(string $filename, string $textline1, string $textline2, string $textline3): string
    {
        return 'foo';
    }

    public function init(): void
    {

    }

    public function createTempSubDir($dirName)
    {
    }
}
