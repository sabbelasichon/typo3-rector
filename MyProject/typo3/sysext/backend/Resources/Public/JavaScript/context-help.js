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
import"bootstrap";import Popover from"@typo3/backend/popover.js";import RegularEvent from"@typo3/core/event/regular-event.js";import DocumentService from"@typo3/core/document-service.js";class ContextHelp{constructor(){this.trigger="click",this.placement="auto",this.selector=".help-link",this.initialize()}async initialize(){await DocumentService.ready();const e=document.querySelectorAll(this.selector);e.forEach((e=>{e.dataset.bsHtml="true",e.dataset.bsPlacement=this.placement,e.dataset.bsTrigger=this.trigger,Popover.popover(e)})),new RegularEvent("show.bs.popover",(e=>{const t=e.target,o=t.dataset.description;if(o){const e={title:t.dataset.title||"",content:o};Popover.setOptions(t,e)}})).delegateTo(document,this.selector),new RegularEvent("click",(t=>{const o=t.target;e.forEach((e=>{e.isEqualNode(o)||Popover.hide(e)}))})).delegateTo(document,"body")}}export default new ContextHelp;