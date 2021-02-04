<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ValueObject\PhpDocNode\Doctrine;

use Rector\BetterPhpDocParser\Contract\PhpDocNode\ShortNameAwareTagInterface;
use Rector\BetterPhpDocParser\ValueObject\PhpDocNode\AbstractTagValueNode;

final class InjectTagValueNode extends AbstractTagValueNode implements ShortNameAwareTagInterface
{
    /**
     * @var string
     */
    public const NAME = '@Extbase\Inject';

    public function getShortName(): string
    {
        return self::NAME;
    }
}
