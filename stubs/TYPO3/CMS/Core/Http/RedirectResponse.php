<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Http;

if (class_exists('TYPO3\CMS\Core\Http\RedirectResponse')) {
    return;
}

final class RedirectResponse extends Response
{

}
