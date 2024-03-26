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
import DocumentService from"@typo3/core/document-service.js";import RegularEvent from"@typo3/core/event/regular-event.js";class Toolbar{static initialize(){Toolbar.initializeEvents()}static initializeEvents(){new RegularEvent("click",(()=>{const e=document.querySelector(".scaffold");e.classList.remove("scaffold-modulemenu-expanded"),e.classList.toggle("scaffold-toolbar-expanded")})).bindTo(document.querySelector(".t3js-topbar-button-toolbar")),new RegularEvent("click",(()=>{document.querySelector(".scaffold").classList.remove("scaffold-modulemenu-expanded","scaffold-toolbar-expanded")})).bindTo(document.querySelector(".t3js-topbar-button-search"))}}DocumentService.ready().then(Toolbar.initialize);