<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Core\Database\Query;

class NamedParameterNotSupportedForPreparedStatementException extends \InvalidArgumentException
{
    public static function new(string $placeholderName): NamedParameterNotSupportedForPreparedStatementException
    {
        return new self(
            sprintf(
                "Cannot prepare statement for QueryBuilder because unsupported named placeholder '%s'",
                $placeholderName
            ),
            1639249867
        );
    }
}
