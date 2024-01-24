<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Http;


if (class_exists('TYPO3\CMS\Core\Http\JsonResponse')) {
    return;
}

class JsonResponse extends Response
{
}
