<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\ExtbaseControllerActionsMustReturnResponseInterfaceRector\Source;

final class JsonResponse extends \TYPO3\CMS\Core\Http\JsonResponse
{
    public static function fromArray(): self
    {
        return new self();
    }
}
