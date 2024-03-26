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
import $ from"jquery";import Severity from"@typo3/install/renderable/severity.js";class FlashMessage{constructor(){this.template=$('<div class="t3js-message typo3-message alert"><h4></h4><p class="messageText"></p></div>')}render(e,s,t){const a=this.template.clone();return a.addClass("alert-"+Severity.getCssClass(e)),s&&a.find("h4").text(s),t?a.find(".messageText").text(t):a.find(".messageText").remove(),a}}export default new FlashMessage;