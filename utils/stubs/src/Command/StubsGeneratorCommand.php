<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Stubs\Command;

use Nette\Utils\Strings;
use Rector\RectorGenerator\TemplateFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Console\ShellCode;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

final class StubsGeneratorCommand extends Command
{
    /**
     * @var SmartFileSystem
     */
    private $smartFileSystem;

    /**
     * @var TemplateFactory
     */
    private $templateFactory;

    public function __construct(SmartFileSystem $smartFileSystem, TemplateFactory $templateFactory)
    {
        parent::__construct();
        $this->smartFileSystem = $smartFileSystem;
        $this->templateFactory = $templateFactory;
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setAliases(['stubs-generator']);
        $this->setDescription('[DEV] Generate real stubs from ClassAliasMaps');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $finder = new Finder();
        $migrationsDirectory = __DIR__ . '/../../../../Migrations';
        $stubsDirectory = __DIR__ . '/../../../../stubs/Migrations';
        $classTemplate = __DIR__ . '/../../templates/StubClassTemplate.php';
        $interfaceTemplate = __DIR__ . '/../../templates/StubInterfaceTemplate.php';

        $finder->in($migrationsDirectory)
            ->files()
            ->name('ClassAliasMap.php');

        foreach ($finder as $classMap) {
            $output->writeln(sprintf('Process ClassAliasMap %s', $classMap->getRealPath()));

            $classAliasMap = require_once $classMap->getRealPath();

            if (! is_array($classAliasMap)) {
                continue;
            }

            $stubs = array_keys($classAliasMap);

            foreach ($stubs as $stub) {
                $className = $stub;
                $namespace = '';
                if (Strings::contains($stub, '\\')) {
                    $namespaceParts = explode('\\', $stub);

                    $className = array_pop($namespaceParts);
                    $namespace = sprintf('namespace %s;', implode('\\', $namespaceParts));
                }

                $stubFile = sprintf('%s/%s.php', $stubsDirectory, $className);

                $this->smartFileSystem->touch($stubFile);

                $templateFileName = $classTemplate;
                if (Strings::endsWith($stub, 'Interface')) {
                    $templateFileName = $interfaceTemplate;
                }

                $templateFile = new SmartFileInfo($templateFileName);

                $templateVariables = [
                    '__CLASSNAME__' => $className,
                    '__NAMESPACE__' => $namespace,
                ];

                $content = $this->templateFactory->create($templateFile->getContents(), $templateVariables);

                $this->smartFileSystem->dumpFile($stubFile, $content);

                $output->writeln(sprintf('Create stub for %s', $stub));
            }
        }

        return ShellCode::SUCCESS;
    }
}
