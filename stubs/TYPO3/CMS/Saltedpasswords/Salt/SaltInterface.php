<?php


namespace TYPO3\CMS\Saltedpasswords\Salt;;

if (interface_exists(SaltInterface::class)) {
    return;
}

interface SaltInterface
{
}
