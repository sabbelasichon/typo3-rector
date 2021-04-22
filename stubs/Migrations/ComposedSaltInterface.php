<?php

namespace TYPO3\CMS\Saltedpasswords\Salt;;

if (interface_exists(ComposedSaltInterface::class)) {
    return;
}

interface ComposedSaltInterface
{
}
