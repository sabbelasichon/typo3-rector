<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\Tests;

use PHPUnit\Framework\TestCase;
use Ssch\TYPO3Rector\ComposerPackages\ComposerPackageParser;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ComposerPackage;
use Ssch\TYPO3Rector\ValueObject\ReplacePackage;
use UnexpectedValueException;

final class ComposerPackageParserTest extends TestCase
{
    /**
     * @var ComposerPackageParser
     */
    private $subject;

    protected function setUp(): void
    {
        $this->subject = new ComposerPackageParser();
    }

    public function testParsePackage(): void
    {
        $extensions = $this->subject->parsePackage($this->packageJson(), new ComposerPackage('georgringer/news'));

        $replacePackages = [];
        foreach ($extensions as $extension) {
            $replacePackage = $extension->getReplacePackage();
            if ($replacePackage instanceof ReplacePackage) {
                $replacePackages[$replacePackage->getOldPackageName()] = $replacePackage;
            }
        }

        self::assertArrayHasKey('typo3-ter/news', $replacePackages);
        self::assertCount(8, $extensions);
    }

    public function testParsePackageReturnEmptyCollection(): void
    {
        $extensions = $this->subject->parsePackage($this->packageJson(), new ComposerPackage('news/news'));

        self::assertCount(0, $extensions);
    }

    public function testParsePackages(): void
    {
        $packages = $this->subject->parsePackages($this->packagesJson());
        self::assertCount(2377, $packages);
    }

    public function testParsePackagesReturnsEmptyArray(): void
    {
        $packages = $this->subject->parsePackages('{}');
        self::assertSame([], $packages);
    }

    private function packagesJson(): string
    {
        $content = file_get_contents(__DIR__ . '/fixtures/packages.json');

        if (false === $content) {
            throw new UnexpectedValueException('Could not open file');
        }

        return $content;
    }

    private function packageJson(): string
    {
        $content = file_get_contents(__DIR__ . '/fixtures/news.json');

        if (false === $content) {
            throw new UnexpectedValueException('Could not open file');
        }

        return $content;
    }
}
