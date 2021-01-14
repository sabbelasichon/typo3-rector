<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Core;

if (class_exists(SystemEnvironmentBuilder::class)) {
    return;
}

final class SystemEnvironmentBuilder
{
    /** @internal */
    public const REQUESTTYPE_FE = 1;
    /** @internal */
    public const REQUESTTYPE_BE = 2;
    /** @internal */
    public const REQUESTTYPE_CLI = 4;
    /** @internal */
    public const REQUESTTYPE_AJAX = 8;
    /** @internal */
    public const REQUESTTYPE_INSTALL = 16;

    public static function run(int $entryPointLevel = 0, int $requestType = self::REQUESTTYPE_FE)
    {
    }
}
