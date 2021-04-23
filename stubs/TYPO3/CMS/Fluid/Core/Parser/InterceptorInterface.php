<?php

namespace TYPO3\CMS\Fluid\Core\Parser;;

if (interface_exists(InterceptorInterface::class)) {
    return;
}

interface InterceptorInterface
{
}
