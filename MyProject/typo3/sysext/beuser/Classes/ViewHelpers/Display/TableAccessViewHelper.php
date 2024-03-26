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

namespace TYPO3\CMS\Beuser\ViewHelpers\Display;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * @internal
 */
final class TableAccessViewHelper extends AbstractConditionViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('table', 'string', 'Tablename to be checked');
        $this->registerArgument('select', 'array', 'List of allowed tables to select', false, []);
        $this->registerArgument('modify', 'array', 'List of allowed tables to modify', false, []);
    }

    /**
     * @param array{table?: string, select?: array, modify?: array} $arguments
     */
    public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
    {
        $table = (string)($arguments['table'] ?? '');
        $select = (array)($arguments['select'] ?? []);
        $modify = (array)($arguments['modify'] ?? []);
        return array_key_exists($table, $select) || array_key_exists($table, $modify);
    }
}
