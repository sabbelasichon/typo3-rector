<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\FileSystem;

use Nette\Utils\Strings;
use Ssch\TYPO3Rector\Generator\Exception\ShouldNotHappenException;
use Ssch\TYPO3Rector\Generator\Factory\TemplateFactory;
use Symfony\Component\Filesystem\Filesystem;

final class ConfigFilesystem
{
    /**
     * @var string[]
     */
    private const REQUIRED_KEYS = ['__MajorPrefixed__', '__MinorPrefixed__', '__Name__'];

    /**
     * @see https://regex101.com/r/gJ0bHJ/1
     * @var string
     */
    private const LAST_ITEM_REGEX = '#;\n};#';

    /**
     * @readonly
     */
    private Filesystem $filesystem;

    /**
     * @readonly
     */
    private TemplateFactory $templateFactory;

    public function __construct(Filesystem $filesystem, TemplateFactory $templateFactory)
    {
        $this->filesystem = $filesystem;
        $this->templateFactory = $templateFactory;
    }

    /**
     * @param array<string, string> $templateVariables
     */
    public function addRuleToConfigurationFile(
        string $configFilePath,
        array $templateVariables,
        string $rectorFqnNamePattern
    ): void {
        $this->createConfigurationFileIfNotExists($configFilePath);

        $configFileContents = (string) file_get_contents($configFilePath);

        $this->ensureRequiredKeysAreSet($templateVariables);

        // already added?
        $servicesFullyQualifiedName = $this->templateFactory->create($rectorFqnNamePattern, $templateVariables);
        if (\str_contains($configFileContents, $servicesFullyQualifiedName)) {
            return;
        }

        $rule = sprintf('$rectorConfig->rule(\\%s::class);', $servicesFullyQualifiedName);
        // Add new rule to existing ones or add as first rule of new configuration file.
        if (Strings::match($configFileContents, self::LAST_ITEM_REGEX)) {
            $registerServiceLine = sprintf(';' . PHP_EOL . '    %s' . PHP_EOL . '};', $rule);
            $configFileContents = Strings::replace($configFileContents, self::LAST_ITEM_REGEX, $registerServiceLine);
        } else {
            $configFileContents = str_replace('###FIRST_RULE###', $rule, $configFileContents);
        }

        // Print the content back to file
        $this->filesystem->dumpFile($configFilePath, $configFileContents);
    }

    /**
     * @param array<string, string> $templateVariables
     */
    private function ensureRequiredKeysAreSet(array $templateVariables): void
    {
        $missingKeys = array_diff(self::REQUIRED_KEYS, array_keys($templateVariables));
        if ($missingKeys === []) {
            return;
        }

        $message = sprintf('Template variables for "%s" keys are missing', implode('", "', $missingKeys));
        throw new ShouldNotHappenException($message);
    }

    private function createConfigurationFileIfNotExists(string $configFilePath): void
    {
        if ($this->filesystem->exists($configFilePath)) {
            return;
        }

        $parentDirectory = dirname($configFilePath);
        $this->filesystem->mkdir($parentDirectory);
        $this->filesystem->touch($configFilePath);
        $this->filesystem->appendToFile(
            $configFilePath,
            (string) file_get_contents(__DIR__ . '/../../templates/config/config.php'),
            true
        );
    }
}
