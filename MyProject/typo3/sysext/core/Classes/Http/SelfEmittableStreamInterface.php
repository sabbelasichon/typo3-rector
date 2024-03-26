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

namespace TYPO3\CMS\Core\Http;

use Psr\Http\Message\StreamInterface;

/**
 * A PSR-7 stream which allows to be emitted on its own.
 *
 * @internal
 */
interface SelfEmittableStreamInterface extends StreamInterface
{
    /**
     * Output the contents of the stream to the output buffer
     */
    public function emit();
}
