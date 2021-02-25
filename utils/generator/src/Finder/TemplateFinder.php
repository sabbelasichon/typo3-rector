<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\Finder;

use Symplify\SmartFileSystem\FileSystemGuard;
use Symplify\SmartFileSystem\Finder\FinderSanitizer;
use Symplify\SmartFileSystem\SmartFileInfo;

final class TemplateFinder
{
    /**
     * @var string
     */
    public const TEMPLATES_DIRECTORY = __DIR__ . '/../../templates';

    /**
     * @var FinderSanitizer
     */
    private $finderSanitizer;

    /**
     * @var FileSystemGuard
     */
    private $fileSystemGuard;

    public function __construct(FinderSanitizer $finderSanitizer, FileSystemGuard $fileSystemGuard)
    {
        $this->finderSanitizer = $finderSanitizer;
        $this->fileSystemGuard = $fileSystemGuard;
    }

    /**
     * @return SmartFileInfo[]
     */
    public function find(): array
    {
        $filePaths = $this->addRuleAndTestCase();

        $this->ensureFilePathsExists($filePaths);

        return $this->finderSanitizer->sanitize($filePaths);
    }

    private function addRuleAndTestCase(): array
    {
        $filePaths = [];

        $filePaths[] = __DIR__ . '/../../templates/src/Rector/__Major__/__Minor__/__Name__.php';
        $filePaths[] = __DIR__ . '/../../templates/tests/Rector/__Major__/__Minor__/__Test_Directory__/__Name__Test.php.inc';
        $filePaths[] = __DIR__ . '/../../templates/tests/Rector/__Major__/__Minor__/__Test_Directory__/Fixture/fixture.php.inc';
        $filePaths[] = __DIR__ . '/../../templates/tests/Rector/__Major__/__Minor__/__Test_Directory__/config/configured_rule.php';

        return $filePaths;
    }

    /**
     * @param string[] $filePaths
     */
    private function ensureFilePathsExists(array $filePaths): void
    {
        foreach ($filePaths as $filePath) {
            $this->fileSystemGuard->ensureFileExists($filePath, __METHOD__);
        }
    }
}
