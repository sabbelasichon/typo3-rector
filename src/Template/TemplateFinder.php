<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Template;

use Symplify\SmartFileSystem\SmartFileInfo;

final class TemplateFinder
{
    /**
     * @readonly
     */
    private string $templateDirectory;

    public function __construct()
    {
        $this->templateDirectory = __DIR__ . '/../../templates/maker/';
    }

    public function getCommand(): SmartFileInfo
    {
        return $this->createSmartFileInfo('Commands/Command.tpl');
    }

    private function createSmartFileInfo(string $template): SmartFileInfo
    {
        return new SmartFileInfo($this->templateDirectory . $template);
    }
}
