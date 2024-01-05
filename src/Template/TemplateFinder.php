<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Template;

use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;
use Symfony\Component\Finder\SplFileInfo;

final class TemplateFinder
{
    /**
     * @readonly
     */
    private string $templateDirectory;

    /**
     * @readonly
     */
    private FileInfoFactory $fileInfoFactory;

    public function __construct(FileInfoFactory $fileInfoFactory)
    {
        $this->templateDirectory = __DIR__ . '/../../templates/maker/';
        $this->fileInfoFactory = $fileInfoFactory;
    }

    public function getCommand(): SplFileInfo
    {
        return $this->fileInfoFactory->createFileInfoFromPath($this->templateDirectory . 'Commands/Command.tpl');
    }
}
