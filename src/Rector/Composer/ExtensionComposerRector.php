<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Composer;

use Ssch\TYPO3Rector\Composer\ExtensionComposerRectorInterface;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @changelog https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ExtensionArchitecture/ComposerJson/Index.html#extra
 * @see \Ssch\TYPO3Rector\Tests\Rector\Composer\ExtensionComposerRector\ExtensionComposerRectorTest
 */
final class ExtensionComposerRector implements ExtensionComposerRectorInterface
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

        $this->addExtensionKey($composerJson);
        $this->addDescription($composerJson);
        $this->addLicense($composerJson);
        $this->fixPackageName($composerJson);
    }

    /**
     * @param array<string, string[]> $configuration
     */
    public function configure(array $configuration): void
    {
        $this->defaultTypo3VersionConstraint = $configuration[self::TYPO3_VERSION_CONSTRAINT] ?? '*';
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add extra extension_key in `composer.json` and add option default constraint', [new ConfiguredCodeSample(
            <<<'CODE_SAMPLE'
{
    "require": {
      "typo3/cms-core": "^9.5"
   },
    "extra": {}
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
{
   "require": {
      "typo3/cms-core": "^10.4"
   },
   "extra": {
      "typo3/cms": {
         "extension-key": "my_extension"
      }
   }
}
CODE_SAMPLE
            , [
                self::TYPO3_VERSION_CONSTRAINT => '^10.4',
            ]
        ),
        ]);
    }

    private function addExtensionKey(ComposerJson $composerJson): void
    {
        $extra = $composerJson->getExtra();

        if (isset($extra['typo3/cms']['extension-key'])) {
            return;
        }

        $fileInfo = $composerJson->getFileInfo();

        if (! $fileInfo instanceof SmartFileInfo) {
            return;
        }

        $extra['typo3/cms']['extension-key'] = \basename(\dirname($fileInfo->getRealPath()));

        $composerJson->setExtra($extra);
    }

    private function addDescription(ComposerJson $composerJson): void
    {
        $description = $composerJson->getDescription();

        if ('' !== $description && null !== $description) {
            return;
        }

        $composerJson->setDescription('Add description...');
    }

    private function addLicense(ComposerJson $composerJson): void
    {
        $license = $composerJson->getLicense();

        if ('' !== $license && null !== $license && [] !== $license) {
            return;
        }

        $composerJson->setLicense('GPL-2.0-or-later');
    }

    private function fixPackageName(ComposerJson $composerJson): void
    {
        $name = $composerJson->getName();

        if ('' === $name) {
            return;
        }

        if (null === $name) {
            return;
        }

        if (false === strpos($name, '_')) {
            return;
        }

        $composerJson->setName(str_replace('_', '-', $name));
    }
}
