<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\ExtbaseActionsWithRedirectMustReturnResponseInterfaceRector\Source;

final class JsonResponse extends \TYPO3\CMS\Core\Http\JsonResponse
{
    public static function fromArray(): self
    {
        return new self();
    }
}
