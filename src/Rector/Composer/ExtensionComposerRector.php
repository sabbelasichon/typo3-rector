<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Composer;

use Rector\Composer\Contract\Rector\ComposerRectorInterface;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @see \Ssch\TYPO3Rector\Tests\Rector\Composer\ExtensionComposerRector\ExtensionComposerRectorTest
 */
final class ExtensionComposerRector implements ComposerRectorInterface
{
    /**
     * @var string
     */
    public const TYPO3_VERSION_CONSTRAINT = 'typo3_version_constraint';

    /**
     * @var mixed|string|string[]
     */
    private $defaultTypo3VersionConstraint = '';

    public function refactor(ComposerJson $composerJson): void
    {
        if ('typo3-cms-extension' !== $composerJson->getType()) {
            return;
        }

        if ('' !== $this->defaultTypo3VersionConstraint) {
            $composerJson->addRequiredPackage('typo3/cms-core', $this->defaultTypo3VersionConstraint);
            $composerJson->changePackageVersion('typo3/cms-core', $this->defaultTypo3VersionConstraint);
        }

        $extra = $composerJson->getExtra();

        if (isset($extra['typo3/cms']['extension-key'])) {
            return;
        }

        $fileInfo = $composerJson->getFileInfo();

        if (! $fileInfo instanceof SmartFileInfo) {
            return;
        }

        $extra['typo3/cms'] = [
            'extension-key' => basename(dirname($fileInfo->getRealPath())),
        ];

        $composerJson->setExtra($extra);
    }

    /**
     * @param array<string, string[]> $configuration
     */
    public function configure(array $configuration): void
    {
        $this->defaultTypo3VersionConstraint = $configuration[self::TYPO3_VERSION_CONSTRAINT] ?? '*';
    }
}
