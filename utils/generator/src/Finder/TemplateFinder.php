<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\Finder;

use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;
use Symfony\Component\Finder\SplFileInfo;

final class TemplateFinder
{
    /**
     * @var string
     */
    public const TEMPLATES_DIRECTORY = __DIR__ . '/../../templates';

    /**
     * @readonly
     */
    private FileInfoFactory $fileInfoFactory;

    public function __construct(FileInfoFactory $fileInfoFactory)
    {
        $this->fileInfoFactory = $fileInfoFactory;
    }

    /**
     * @return SplFileInfo[]
     */
    public function find(): array
    {
        $filePaths = $this->addRuleAndTestCase();

        $smartFileInfos = [];
        foreach ($filePaths as $filePath) {
            $smartFileInfos[] = $this->fileInfoFactory->createFileInfoFromPath($filePath);
        }

        return $smartFileInfos;
    }

    /**
     * @return array<int, string>
     */
    private function addRuleAndTestCase(): array
    {
        return [
            __DIR__ . '/../../templates/rules/__Major__/__MinorPrefixed__/__Name__.php',
            __DIR__ . '/../../templates/tests/Rector/__MajorPrefixed__/__MinorPrefixed__/__Test_Directory__/__Name__Test.php.inc',
            __DIR__ . '/../../templates/tests/Rector/__MajorPrefixed__/__MinorPrefixed__/__Test_Directory__/Fixture/fixture.php.inc',
            __DIR__ . '/../../templates/tests/Rector/__MajorPrefixed__/__MinorPrefixed__/__Test_Directory__/config/configured_rule.php.inc',
        ];
    }
}
