<?php

declare(strict_types=1);

namespace TYPO3\CMS\Saltedpasswords\Salt;

if (class_exists(Pbkdf2Salt::class)) {
    return;
}

class Pbkdf2Salt
{

}
