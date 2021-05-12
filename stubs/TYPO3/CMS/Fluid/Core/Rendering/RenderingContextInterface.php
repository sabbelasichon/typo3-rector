<?php

declare(strict_types=1);

namespace TYPO3\CMS\Fluid\Core\Rendering;;

if (interface_exists(RenderingContextInterface::class)) {
    return;
}

interface RenderingContextInterface
{
}
