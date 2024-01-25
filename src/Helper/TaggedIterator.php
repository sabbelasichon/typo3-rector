<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use function RectorPrefix202306\Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

final class TaggedIterator
{
    public static function tagged_iterator(string $tag)
    {
        return tagged_iterator($tag);
    }
}
