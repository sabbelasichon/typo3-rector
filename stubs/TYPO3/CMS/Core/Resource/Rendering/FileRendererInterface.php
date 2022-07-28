<?php

namespace TYPO3\CMS\Core\Resource\Rendering;

if (interface_exists('TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface')) {
    return;
}

use TYPO3\CMS\Core\Resource\FileInterface;

/**
 * Class FileRendererInterface
 */
interface FileRendererInterface
{
    /**
     * Render for given File(Reference) HTML output
     *
     * @param FileInterface $file
     * @param int|string $width TYPO3 known format; examples: 220, 200m or 200c
     * @param int|string $height TYPO3 known format; examples: 220, 200m or 200c
     * @param array $options
     * @param bool $usedPathsRelativeToCurrentScript See $file->getPublicUrl()
     * @return string
     */
    public function render(FileInterface $file, $width, $height, array $options = [], $usedPathsRelativeToCurrentScript = false);
}
