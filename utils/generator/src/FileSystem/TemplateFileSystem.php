<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\FileSystem;

use Nette\Utils\Strings;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Ssch\TYPO3Rector\Generator\Finder\TemplateFinder;
use Symplify\SmartFileSystem\SmartFileInfo;

final class TemplateFileSystem
{
    /**
     * @var string
     * @see https://regex101.com/r/fw3jBe/1
     */
    private const FIXTURE_SHORT_REGEX = '#/Fixture/#';

    /**
     * @param string[] $templateVariables
     */
    public function resolveDestination(
        SmartFileInfo $smartFileInfo,
        array $templateVariables,
        string $targetDirectory
    ): string {
        $destination = $smartFileInfo->getRelativeFilePathFromDirectory(TemplateFinder::TEMPLATES_DIRECTORY);
        $destination = $this->applyVariables($destination, $templateVariables);

        // remove ".inc" protection from PHPUnit if not a test case
        if ($this->isNonFixtureFileWithIncSuffix($destination)) {
            $destination = Strings::before($destination, '.inc');
        }

        // special hack for tests, to PHPUnit doesn't load the generated file as test case
        /** @var string $destination */
        if (\str_ends_with($destination, 'Test.php') && StaticPHPUnitEnvironment::isPHPUnitRun()) {
            $destination .= '.inc';
        }

        return $targetDirectory . DIRECTORY_SEPARATOR . $destination;
    }

    /**
     * @param mixed[] $variables
     */
    private function applyVariables(string $content, array $variables): string
    {
        return str_replace(array_keys($variables), array_values($variables), $content);
    }

    private function isNonFixtureFileWithIncSuffix(string $filePath): bool
    {
        if (Strings::match($filePath, self::FIXTURE_SHORT_REGEX)) {
            return false;
        }

        return \str_ends_with($filePath, '.inc');
    }
}
