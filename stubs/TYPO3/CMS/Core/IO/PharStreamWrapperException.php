<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\IO;

if (class_exists(PharStreamWrapperException::class)) {
    return;
}

class PharStreamWrapperException
{

}
