<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Contract\Composer;

use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;

interface ExtensionComposerRectorInterface extends RectorInterface, ConfigurableRectorInterface
{
    public function refactor(ComposerJson $composerJson): void;
}
