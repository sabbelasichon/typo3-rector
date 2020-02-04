<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Console\Command;

use Rector\Console\Shell;
use Rector\Testing\Finder\RectorsFinder;
use Ssch\TYPO3Rector\Console\OutputFormatter\DumpRectorsOutputFormatterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

final class GenerateDocsCommand extends Command
{
    /**
     * @var RectorsFinder
     */
    private $rectorsFinder;

    /**
     * @var DumpRectorsOutputFormatterInterface[]
     */
    private $dumpRectorsOutputFormatterInterfaces = [];

    /**
     * @param DumpRectorsOutputFormatterInterface[] $dumpRectorsOutputFormatterInterfaces
     */
    public function __construct(RectorsFinder $rectorsFinder, array $dumpRectorsOutputFormatterInterfaces)
    {
        parent::__construct();

        $this->rectorsFinder = $rectorsFinder;
        $this->dumpRectorsOutputFormatterInterfaces = $dumpRectorsOutputFormatterInterfaces;
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('[Docs] Dump overview of all TYPO3 Rectors');
        $this->addOption(
            'output-format',
            'o',
            InputOption::VALUE_REQUIRED,
            'Output format for Rectors [json, markdown, typo3-markdown]',
            'markdown'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $generalRectors = $this->rectorsFinder->findInDirectory(__DIR__ . '/../../Rector/');

        foreach ($this->dumpRectorsOutputFormatterInterfaces as $outputFormatter) {
            if ($outputFormatter->getName() !== $input->getOption('output-format')) {
                continue;
            }

            $outputFormatter->format($generalRectors);
        }

        return Shell::CODE_SUCCESS;
    }
}
