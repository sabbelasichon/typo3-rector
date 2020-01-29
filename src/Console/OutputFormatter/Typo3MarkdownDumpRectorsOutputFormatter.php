<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Console\OutputFormatter;

use Rector\ConsoleDiffer\MarkdownDifferAndFormatter;
use Rector\Contract\Rector\RectorInterface;
use Rector\Contract\RectorDefinition\CodeSampleInterface;
use Rector\RectorDefinition\ConfiguredCodeSample;
use Rector\Utils\DocumentationGenerator\Contract\OutputFormatter\DumpRectorsOutputFormatterInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

final class Typo3MarkdownDumpRectorsOutputFormatter implements DumpRectorsOutputFormatterInterface
{
    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    /**
     * @var MarkdownDifferAndFormatter
     */
    private $markdownDifferAndFormatter;

    public function __construct(
        SymfonyStyle $symfonyStyle,
        MarkdownDifferAndFormatter $markdownDifferAndFormatter
    ) {
        $this->symfonyStyle = $symfonyStyle;
        $this->markdownDifferAndFormatter = $markdownDifferAndFormatter;
    }

    public function getName(): string
    {
        return 'typo3-markdown';
    }

    /**
     * @param RectorInterface[] $genericRectors
     * @param RectorInterface[] $packageRectors
     */
    public function format(array $genericRectors, array $packageRectors): void
    {
        $this->symfonyStyle->writeln(sprintf('# All %d Rectors Overview', count($genericRectors)));
        $this->symfonyStyle->newLine();

        $this->symfonyStyle->newLine();

        foreach ($genericRectors as $genericRector) {
            $this->printRector($genericRector);
        }
    }

    private function printRector(RectorInterface $rector): void
    {
        $headline = $this->getRectorClassWithoutNamespace($rector);
        $this->symfonyStyle->writeln(sprintf('### `%s`', $headline));

        $this->symfonyStyle->newLine();
        $this->symfonyStyle->writeln(sprintf('- class: `%s`', get_class($rector)));

        $rectorDefinition = $rector->getDefinition();
        if ('' !== $rectorDefinition->getDescription()) {
            $this->symfonyStyle->newLine();
            $this->symfonyStyle->writeln($rectorDefinition->getDescription());
        }

        foreach ($rectorDefinition->getCodeSamples() as $codeSample) {
            $this->symfonyStyle->newLine();

            $this->printConfiguration($rector, $codeSample);
            $this->printCodeSample($codeSample);
        }

        $this->symfonyStyle->newLine();
        $this->symfonyStyle->writeln('<br>');
        $this->symfonyStyle->newLine();
    }

    private function getRectorClassWithoutNamespace(RectorInterface $rector): string
    {
        $rectorClass = get_class($rector);
        $rectorClassParts = explode('\\', $rectorClass);

        return $rectorClassParts[count($rectorClassParts) - 1];
    }

    private function printConfiguration(RectorInterface $rector, CodeSampleInterface $codeSample): void
    {
        if (!$codeSample instanceof ConfiguredCodeSample) {
            return;
        }

        $configuration = [
            'services' => [
                get_class($rector) => $codeSample->getConfiguration(),
            ],
        ];
        $configuration = Yaml::dump($configuration, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);

        $this->printCodeWrapped($configuration, 'yaml');

        $this->symfonyStyle->newLine();
        $this->symfonyStyle->writeln('↓');
        $this->symfonyStyle->newLine();
    }

    private function printCodeSample(CodeSampleInterface $codeSample): void
    {
        $diff = $this->markdownDifferAndFormatter->bareDiffAndFormatWithoutColors(
            $codeSample->getCodeBefore(),
            $codeSample->getCodeAfter()
        );

        $this->printCodeWrapped($diff, 'diff');
    }

    private function printCodeWrapped(string $content, string $format): void
    {
        $this->symfonyStyle->writeln(sprintf('```%s%s%s%s```', $format, PHP_EOL, rtrim($content), PHP_EOL));
    }
}
