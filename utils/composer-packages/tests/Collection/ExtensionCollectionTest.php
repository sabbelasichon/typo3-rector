<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\Tests\Collection;

use PHPUnit\Framework\TestCase;
use Rector\Composer\ValueObject\PackageAndVersion;
use Ssch\TYPO3Rector\ComposerPackages\Collection\ExtensionCollection;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ExtensionVersion;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\Typo3Version;

final class ExtensionCollectionTest extends TestCase
{
    /**
     * @var ExtensionCollection
     */
    private $subject;

    protected function setUp(): void
    {
        $this->subject = new ExtensionCollection();
    }

    /**
     * @dataProvider extensionsProvider
     *
     * @param ExtensionVersion[] $extensions
     */
    public function testAddExtensions(
        array $extensions,
        ?ExtensionVersion $expectedVersion,
        Typo3Version $typo3Version
    ): void {
        foreach ($extensions as $extension) {
            $this->subject->addExtension($extension);
        }

        $extractedVersion = $this->subject->findHighestVersion($typo3Version);

        $this->assertSame($expectedVersion, $extractedVersion);
    }

    public function extensionsProvider(): array
    {
        return [
            'News Version 7 is selected for TYPO3 version 9.5.99' =>
                [
                    [
                        new ExtensionVersion(
                            new PackageAndVersion('georgringer/news', '5.0'),
                            [new Typo3Version('8.7.99'), new Typo3Version('9.5.99')]
                        ),
                        new ExtensionVersion(
                            new PackageAndVersion('georgringer/news', '7.0'),
                            [new Typo3Version('9.5.99'), new Typo3Version('10.4.99')]
                        ),
                        new ExtensionVersion(
                            new PackageAndVersion('georgringer/news', '6.0'),
                            [new Typo3Version('9.5.99')]
                        ),
                    ],
                    new ExtensionVersion(
                        new PackageAndVersion('georgringer/news', '7.0'),
                        [new Typo3Version('9.5.99'), new Typo3Version('10.4.99')]
                    ),
                    new Typo3Version('9.5.99'),
                ],
            'None found due to misconfiguration of previous version' =>
                [
                    [
                        new ExtensionVersion(
                            new PackageAndVersion('foo/bar', '5.0'),
                            [new Typo3Version('9.5.99'), new Typo3Version('11.0.99')]
                        ),
                        new ExtensionVersion(new PackageAndVersion('foo/bar', '7.0'), [new Typo3Version('9.5.99')]),
                    ],
                    null,
                    new Typo3Version('11.0.99'),
                ],
        ];
    }
}
