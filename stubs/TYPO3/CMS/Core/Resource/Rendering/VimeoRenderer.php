<?php

namespace TYPO3\CMS\Core\Resource\Rendering;

if (class_exists('TYPO3\CMS\Core\Resource\Rendering\VimeoRenderer')) {
    return;
}

use TYPO3\CMS\Core\Resource\FileInterface;

class VimeoRenderer implements FileRendererInterface
{
    /**
     * Render for given File(Reference) HTML output
     *
     * @param FileInterface $file
     * @param int|string $width TYPO3 known format; examples: 220, 200m or 200c
     * @param int|string $height TYPO3 known format; examples: 220, 200m or 200c
     * @param array $options controls = TRUE/FALSE (default TRUE), autoplay = TRUE/FALSE (default FALSE), loop = TRUE/FALSE (default FALSE)
     * @param bool $usedPathsRelativeToCurrentScript See $file->getPublicUrl()
     * @return string
     */
    public function render(FileInterface $file, $width, $height, array $options = [], $usedPathsRelativeToCurrentScript = false)
    {
        return '';
    }
}
