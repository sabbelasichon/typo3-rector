<?php

declare(strict_types=1);

namespace TYPO3\CMS\Saltedpasswords\Salt;

if (class_exists(BlowfishSalt::class)) {
    return;
}

final class BlowfishSalt
{

}
