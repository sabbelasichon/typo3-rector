<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Composer;

use Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;

final class ComposerModifier
{
    /**
     * @var ExtensionComposerRectorInterface[]
     */
    private $composerRectors = [];

    /**
     * @param ExtensionComposerRectorInterface[] $composerRectors
     */
    public function __construct(array $composerRectors)
    {
        $this->composerRectors = $composerRectors;
    }

    public function modify(ComposerJson $composerJson): void
    {
        foreach ($this->composerRectors as $composerRector) {
            $composerRector->refactor($composerJson);
        }
    }

    public function enabled(): bool
    {
        return [] !== $this->composerRectors;
    }
}
