<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Reporting;

use Rector\ChangesReporting\Annotation\AnnotationExtractor;
use Rector\Core\Provider\CurrentFileProvider;
use ReflectionClass;
use Ssch\TYPO3Rector\Reporting\ValueObject\Report;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

final class HtmlReporter implements Reporter
{
    /**
     * @var string
     */
    private const APPEND_MARKER = '<!--APPEND-->';

    /**
     * @var AnnotationExtractor
     */
    private $annotationExtractor;

    /**
     * @var SmartFileInfo
     */
    private $reportFile;

    /**
     * @var SmartFileSystem
     */
    private $smartFileSystem;

    /**
     * @var CurrentFileProvider
     */
    private $currentFileProvider;

    public function __construct(
        AnnotationExtractor $annotationExtractor,
        SmartFileInfo $reportFile,
        SmartFileSystem $smartFileSystem,
        CurrentFileProvider $currentFileProvider
    ) {
        $this->annotationExtractor = $annotationExtractor;
        $this->reportFile = $reportFile;
        $this->smartFileSystem = $smartFileSystem;
        $this->currentFileProvider = $currentFileProvider;
    }

    public function report(Report $report): void
    {
        $file = $this->currentFileProvider->getFile();

        if (null === $file) {
            return;
        }

        $smartFileInfo = $file->getSmartFileInfo();

        $rectorReflection = new ReflectionClass($report->getRector());

        $recordData = [
            'message' => $report->getMessage(),
            'rector' => $rectorReflection->getShortName(),
            'file' => sprintf(
                '<a href="file:///%s" target="_blank" rel="noopener">%s</a>',
                $smartFileInfo->getRealPath(),
                $smartFileInfo->getBasename()
            ),
            'changelog' => '-',
            'suggestions' => '-',
        ];

        $changelog = $this->annotationExtractor->extractAnnotationFromClass($rectorReflection->getName(), '@changelog');
        if (null !== $changelog) {
            $recordData['changelog'] = sprintf('<a href="%s" target="_blank" rel="noopener">Changelog</a>', $changelog);
        }

        if ([] !== $report->getSuggestions()) {
            $recordData['suggestions'] = implode('<br />', $report->getSuggestions());
        }

        $html = [];
        $html[] = '<tr class="report-table__row">';

        foreach ($recordData as $cell) {
            $html[] = sprintf('<td class="report-table__row-cell">%s</td>', $cell);
        }

        $html[] = '</tr>';
        $html[] = self::APPEND_MARKER;

        $content = $this->reportFile->getContents();

        $content = str_replace(self::APPEND_MARKER, implode("\n", $html), $content);

        $this->smartFileSystem->dumpFile($this->reportFile->getRealPath(), $content);
    }
}
