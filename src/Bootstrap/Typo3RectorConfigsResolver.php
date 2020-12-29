<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Bootstrap;

use Rector\Set\RectorSetProvider;
use Ssch\TYPO3Rector\Set\Typo3RectorSetProvider;
use Symfony\Component\Console\Input\ArgvInput;
use Symplify\SetConfigResolver\ConfigResolver;
use Symplify\SetConfigResolver\SetAwareConfigResolver;
use Symplify\SetConfigResolver\SetResolver;
use Symplify\SmartFileSystem\SmartFileInfo;

final class Typo3RectorConfigsResolver
{
    /**
     * @var SetResolver
     */
    private $setResolver;

    /**
     * @var ConfigResolver
     */
    private $configResolver;

    /**
     * @var SetAwareConfigResolver
     */
    private $setAwareConfigResolver;

    public function __construct()
    {
        $rectorSetProvider = new Typo3RectorSetProvider(new RectorSetProvider());
        $this->setResolver = new SetResolver($rectorSetProvider);
        $this->configResolver = new ConfigResolver();
        $this->setAwareConfigResolver = new SetAwareConfigResolver($rectorSetProvider);
    }

    /**
     * @noRector
     */
    public function getFirstResolvedConfig(): ?SmartFileInfo
    {
        return $this->configResolver->getFirstResolvedConfigFileInfo();
    }

    /**
     * @param SmartFileInfo[] $configFileInfos
     * @return SmartFileInfo[]
     */
    public function resolveSetFileInfosFromConfigFileInfos(array $configFileInfos): array
    {
        return $this->setAwareConfigResolver->resolveFromParameterSetsFromConfigFiles($configFileInfos);
    }

    /**
     * @return SmartFileInfo[]
     */
    public function provide(): array
    {
        $configFileInfos = [];

        // Detect configuration from --set
        $argvInput = new ArgvInput();

        $set = $this->setResolver->detectFromInput($argvInput);
        if (null !== $set) {
            $configFileInfos[] = $set;
        }

        // And from --config or default one
        $inputOrFallbackConfigFileInfo = $this->configResolver->resolveFromInputWithFallback(
            $argvInput,
            ['rector.php']
        );

        if (null !== $inputOrFallbackConfigFileInfo) {
            $configFileInfos[] = $inputOrFallbackConfigFileInfo;
        }

        $setFileInfos = $this->resolveSetFileInfosFromConfigFileInfos($configFileInfos);

        if (in_array($argvInput->getFirstArgument(), ['generate', 'g', 'create', 'c'], true)) {
            // autoload rector recipe file if present, just for \Rector\RectorGenerator\Command\GenerateCommand
            $rectorRecipeFilePath = getcwd() . '/rector-recipe.php';
            if (file_exists($rectorRecipeFilePath)) {
                $configFileInfos[] = new SmartFileInfo($rectorRecipeFilePath);
            }
        }

        return array_merge($configFileInfos, $setFileInfos);
    }
}
