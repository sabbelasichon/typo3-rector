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
import"@typo3/backend/input/clearable.js";class BackendUserListing{constructor(){let e;if(null!==(e=document.getElementById("tx_Beuser_username"))){const t=""!==e.value;e.clearable({onClear:e=>{t&&e.closest("form").submit()}})}}}export default new BackendUserListing;