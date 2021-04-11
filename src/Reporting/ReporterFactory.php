<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Reporting;

use DateTimeImmutable;
use Rector\ChangesReporting\Annotation\AnnotationExtractor;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

final class ReporterFactory
{
    /**
     * @var ParameterProvider
     */
    private $parameterProvider;

    /**
     * @var SmartFileSystem
     */
    private $smartFileSystem;

    /**
     * @var AnnotationExtractor
     */
    private $annotationExtractor;

    public function __construct(
        ParameterProvider $parameterProvider,
        SmartFileSystem $smartFileSystem,
        AnnotationExtractor $annotationExtractor
    ) {
        $this->parameterProvider = $parameterProvider;
        $this->smartFileSystem = $smartFileSystem;
        $this->annotationExtractor = $annotationExtractor;
    }

    public function createReporter(): Reporter
    {
        $reportDirectory = (string) $this->parameterProvider->provideParameter(Typo3Option::REPORT_DIRECTORY);

        if (! $this->smartFileSystem->exists($reportDirectory)) {
            return new NullReporter();
        }

        $this->smartFileSystem->remove($reportDirectory);
        $this->smartFileSystem->mirror(__DIR__ . '/../../templates/report/', $reportDirectory);

        $reportFile = new SmartFileInfo(rtrim($reportDirectory, '/') . '/index.html');
        $content = $reportFile->getContents();
        $content = str_replace('###DATE###', (new DateTimeImmutable())->format('d.m.Y'), $content);

        $this->smartFileSystem->dumpFile($reportFile->getRealPath(), $content);

        return new HtmlReporter($this->annotationExtractor, $reportFile, $this->smartFileSystem);
    }
}
