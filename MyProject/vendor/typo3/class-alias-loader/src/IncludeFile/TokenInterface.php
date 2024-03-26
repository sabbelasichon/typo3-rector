<?php
namespace TYPO3\ClassAliasLoader\IncludeFile;

/*
 * This file is part of the class alias loader package.
 *
 * (c) Helmut Hummel <info@helhum.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    public function getContent($includeFilePath);
}
