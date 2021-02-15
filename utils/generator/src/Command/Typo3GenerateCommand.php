<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\Command;

use Nette\Utils\Strings;
use Rector\Core\Exception\ShouldNotHappenException;
use RuntimeException;
use Ssch\TYPO3Rector\Generator\Config\ConfigFilesystem;
use Ssch\TYPO3Rector\Generator\Finder\TemplateFinder;
use Ssch\TYPO3Rector\Generator\Generator\FileGenerator;
use Ssch\TYPO3Rector\Generator\ValueObject\Description;
use Ssch\TYPO3Rector\Generator\ValueObject\Name;
use Ssch\TYPO3Rector\Generator\ValueObject\Typo3RectorRecipe;
use Ssch\TYPO3Rector\Generator\ValueObject\Typo3Version;
use Ssch\TYPO3Rector\Generator\ValueObject\Url;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Console\ShellCode;
use Symplify\SmartFileSystem\SmartFileInfo;

final class Typo3GenerateCommand extends Command
{
    /**
     * @var TemplateFinder
     */
    private $templateFinder;

    /**
     * @var FileGenerator
     */
    private $fileGenerator;

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    /**
     * @var ConfigFilesystem
     */
    private $configFilesystem;

    public function __construct(
        TemplateFinder $templateFinder,
        FileGenerator $fileGenerator,
        SymfonyStyle $symfonyStyle,
        ConfigFilesystem $configFilesystem
    ) {
        parent::__construct();

        $this->templateFinder = $templateFinder;
        $this->fileGenerator = $fileGenerator;
        $this->symfonyStyle = $symfonyStyle;
        $this->configFilesystem = $configFilesystem;
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setAliases(['typo3-create', 'typo3-rector']);
        $this->setDescription('[DEV] Create a new TYPO3 Rector, in a proper location, with new tests');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        /** @var Typo3Version $typo3Version */
        $typo3Version = $helper->ask($input, $output, $this->askForTypo3Version());
        $urlToRstFile = $helper->ask($input, $output, $this->askForRstFile());
        $name = $helper->ask($input, $output, $this->askForName());
        $description = $helper->ask($input, $output, $this->askForDescription());

        $recipe = new Typo3RectorRecipe($typo3Version, $urlToRstFile, $name, Description::createFromString(
            $description
        ));

        $templateFileInfos = $this->templateFinder->find();

        $templateVariables = [
            '__Major__' => $recipe->getMajorVersion(),
            '__Minor__' => $recipe->getMinorVersion(),
            '__Name__' => $recipe->getRectorName(),
            '__Test_Directory__' => $recipe->getTestDirectory(),
            '__Rst_File__' => $recipe->getUrlToRstFile(),
            '__Description__' => $recipe->getDescription(),
        ];

        $targetDirectory = getcwd();

        $generatedFilePaths = $this->fileGenerator->generateFiles(
            $templateFileInfos,
            $templateVariables,
            $targetDirectory
        );

        $testCaseDirectoryPath = $this->resolveTestCaseDirectoryPath($generatedFilePaths);

        $this->configFilesystem->appendRectorServiceToSet($recipe, $templateVariables);

        $this->printSuccess($recipe->getRectorName(), $generatedFilePaths, $testCaseDirectoryPath);

        return ShellCode::SUCCESS;
    }

    protected function askForRstFile(): Question
    {
        $whatIsTheUrlToRstFile = new Question('Url to rst file: ');
        $whatIsTheUrlToRstFile->setNormalizer(function ($url) {
            return Url::createFromString(trim($url));
        });

        return $whatIsTheUrlToRstFile;
    }

    private function askForTypo3Version(): Question
    {
        $whatTypo3Version = new Question('TYPO3-Version (i.e. 8.1): ');
        $whatTypo3Version->setNormalizer(function ($version) {
            return Typo3Version::createFromString(trim($version));
        });

        return $whatTypo3Version;
    }

    private function askForName(): Question
    {
        $giveMeYourName = new Question('Name: ');
        $giveMeYourName->setNormalizer(function ($name) {
            return Name::createFromString(trim($name));
        });

        return $giveMeYourName;
    }

    private function askForDescription(): Question
    {
        $description = new Question('Description: ');
        $description->setValidator(function ($description) {
            if (! is_string($description)) {
                throw new RuntimeException('The description must not be empty');
            }

            return $description;
        });

        return $description;
    }

    /**
     * @param string[] $generatedFilePaths
     */
    private function printSuccess(string $name, array $generatedFilePaths, string $testCaseFilePath): void
    {
        $message = sprintf('New files generated for "%s":', $name);
        $this->symfonyStyle->title($message);

        sort($generatedFilePaths);

        foreach ($generatedFilePaths as $generatedFilePath) {
            $fileInfo = new SmartFileInfo($generatedFilePath);
            $relativeFilePath = $fileInfo->getRelativeFilePathFromCwd();
            $this->symfonyStyle->writeln(' * ' . $relativeFilePath);
        }

        $message = sprintf('Make tests green again:%svendor/bin/phpunit %s', PHP_EOL . PHP_EOL, $testCaseFilePath);

        $this->symfonyStyle->success($message);
    }

    /**
     * @param string[] $generatedFilePaths
     */
    private function resolveTestCaseDirectoryPath(array $generatedFilePaths): string
    {
        foreach ($generatedFilePaths as $generatedFilePath) {
            if (! Strings::endsWith($generatedFilePath, 'Test.php')) {
                continue;
            }

            $generatedFileInfo = new SmartFileInfo($generatedFilePath);
            return dirname($generatedFileInfo->getRelativeFilePathFromCwd());
        }

        throw new ShouldNotHappenException();
    }
}
