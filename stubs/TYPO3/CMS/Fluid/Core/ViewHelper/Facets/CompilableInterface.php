<?php

declare(strict_types=1);

namespace TYPO3\CMS\Fluid\Core\ViewHelper\Facets;;

if (interface_exists(CompilableInterface::class)) {
    return;
}

interface CompilableInterface
{
}
