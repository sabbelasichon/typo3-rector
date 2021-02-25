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

    public function testAddExtensions(): void
    {
        $version5 = new ExtensionVersion(
            new PackageAndVersion('georgringer/news', '5.0'),
            [new Typo3Version('8.7.99'), new Typo3Version('9.5.99')]
        );

        $version7 = new ExtensionVersion(
            new PackageAndVersion('georgringer/news', '7.0'),
            [new Typo3Version('9.5.99'), new Typo3Version('10.4.99')]
        );

        $version6 = new ExtensionVersion(
            new PackageAndVersion('georgringer/news', '6.0'),
            [new Typo3Version('9.5.99')]
        );
        $this->subject->addExtension($version6);
        $this->subject->addExtension($version7);
        $this->subject->addExtension($version5);

        $lowestVersion = $this->subject->findLowestVersion(new Typo3Version('9.5.99'));

        $this->assertSame($version5, $lowestVersion);
    }
}
