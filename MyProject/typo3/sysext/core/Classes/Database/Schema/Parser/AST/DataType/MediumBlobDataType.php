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

namespace TYPO3\CMS\Core\Database\Schema\Parser\AST\DataType;

/**
 * Node representing the MEDIUMBLOB SQL column type
 */
class MediumBlobDataType extends BlobDataType
{
    /**
     * MediumBlobDataType constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // MySQL MEDIUMBLOB can store 16MB
        $this->length = 16777215;
    }
}
