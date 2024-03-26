<?php

/*
 * This file is part of the TYPO3 project.
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

namespace TYPO3\CMS\Composer\Plugin\Core\IncludeFile;

interface TokenInterface
{
    /**
     * The name of the token that shall be replaced
     *
     * @return string
     */
    public function getName();

    /**
     * The content the token should be replaced with
     *
     * @param string $includeFilePath
     * @return string
     */
    public function getContent(string $includeFilePath);
}
