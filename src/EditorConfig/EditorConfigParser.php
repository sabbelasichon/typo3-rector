<?php

namespace Ssch\TYPO3Rector\EditorConfig;

use Ssch\TYPO3Rector\ValueObject\EditorConfigConfiguration;
use Symplify\SmartFileSystem\SmartFileInfo;

interface EditorConfigParser
{
    public function extractConfigurationForFile(
        SmartFileInfo $smartFileInfo,
        EditorConfigConfiguration $defaultEditorConfiguration
    ): EditorConfigConfiguration;
}
