<?php

namespace TYPO3\CMS\Core;

if (class_exists(SingletonInterface::class)) {
    return;
}

interface SingletonInterface
{
}
