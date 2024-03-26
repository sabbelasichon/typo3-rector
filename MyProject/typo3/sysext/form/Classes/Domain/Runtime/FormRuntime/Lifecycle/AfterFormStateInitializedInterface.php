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

namespace TYPO3\CMS\Form\Domain\Runtime\FormRuntime\Lifecycle;

use TYPO3\CMS\Form\Domain\Runtime\FormRuntime;

/**
 * Event is triggered with current form state and form session, which is
 * not the case with e.g. `afterBuildingFinished`. Can be used to further
 * enrich components with runtime state.
 * @internal
 */
interface AfterFormStateInitializedInterface
{
    /**
     * @param FormRuntime $formRuntime holding current form state and static form definition
     */
    public function afterFormStateInitialized(FormRuntime $formRuntime): void;
}
