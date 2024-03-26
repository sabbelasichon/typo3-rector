<?php

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

namespace TYPO3\CMS\Core\Configuration\TypoScript\Exception;

use TYPO3\CMS\Core\Exception;

/**
 * A "Your TypoScript condition is invalid" exception
 * used when a TypoScript condition is called with invalid syntax.
 *
 * @deprecated since TYPO3 v12. Remove together with AbstractConditionMatcher in v13.
 */
class InvalidTypoScriptConditionException extends Exception {}
