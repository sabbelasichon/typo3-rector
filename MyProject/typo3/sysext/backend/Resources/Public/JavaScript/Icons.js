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
import AjaxRequest from"@typo3/core/ajax/ajax-request.js";import ClientStorage from"@typo3/backend/storage/client.js";import{Sizes,States,MarkupIdentifiers}from"@typo3/backend/enum/icon-types.js";import{css,unsafeCSS}from"lit";import{DedupeAsyncTask}from"@typo3/core/cache/dedupe-async-task.js";export class IconStyles{static getStyles(){return[css`
        :host {
          --icon-color-primary: currentColor;
          --icon-size-small: 16px;
          --icon-size-medium: 32px;
          --icon-size-large: 48px;
          --icon-size-mega: 64px;
          --icon-unify-modifier: 0.86;
          --icon-opacity-disabled: 0.5

          display: inline-block;
        }

        .icon-wrapper {
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .icon {
          position: relative;
          display: inline-flex;
          overflow: hidden;
          white-space: nowrap;
          color: var(--icon-color-primary);
          height: var(--icon-size, 1em);
          width: var(--icon-size, 1em);
          line-height: var(--icon-size, 1em);
          flex-shrink: 0;
        }

        .icon img, .icon svg {
          display: block;
          height: 100%;
          width: 100%
        }

        .icon * {
          display: block;
          line-height: inherit
        }

        .icon-markup {
          position: absolute;
          display: block;
          text-align: center;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0
        }

        .icon-overlay {
          position: absolute;
          bottom: 0;
          right: 0;
          height: 68.75%;
          width: 68.75%;
          text-align: center
        }

        .icon-spin .icon-markup {
          -webkit-animation: icon-spin 2s infinite linear;
          animation: icon-spin 2s infinite linear
        }

        @keyframes icon-spin {
          0% {
            transform: rotate(0)
          }
          100% {
            transform: rotate(360deg)
          }
        }

        .icon-state-disabled .icon-markup {
          opacity: var(--icon-opacity-disabled)
        }
      `,IconStyles.getStyleSizeVariant(Sizes.small),IconStyles.getStyleSizeVariant(Sizes.default),IconStyles.getStyleSizeVariant(Sizes.medium),IconStyles.getStyleSizeVariant(Sizes.large),IconStyles.getStyleSizeVariant(Sizes.mega)]}static getStyleSizeVariant(e){const i=unsafeCSS(e);return css`
      :host([size=${i}]) .icon-size-${i},
      :host([raw]) .icon-size-${i} {
        --icon-size: var(--icon-size-${i})
      }
      :host([size=${i}]) .icon-size-${i} .icon-unify,
      :host([raw]) .icon-size-${i} .icon-unify {
        line-height: var(--icon-size);
        font-size: calc(var(--icon-size) * var(--icon-unify-modifier))
      }
      :host([size=${i}]) .icon-size-${i} .icon-overlay .icon-unify,
      :host([raw]) .icon-size-${i} .icon-overlay .icon-unify {
        line-height: calc(var(--icon-size) / 1.6);
        font-size: calc((var(--icon-size) / 1.6) * var(--icon-unify-modifier))
      }
    `}}class Icons{constructor(){this.sizes=Sizes,this.states=States,this.markupIdentifiers=MarkupIdentifiers,this.promiseCache=new DedupeAsyncTask}getIcon(e,i,t,n,s,o){const c=[e,i=i||Sizes.default,t,n=n||States.default,s=s||MarkupIdentifiers.default],r=c.join("_");return this.getIconRegistryCache().then((e=>(ClientStorage.isset("icon_registry_cache_identifier")&&ClientStorage.get("icon_registry_cache_identifier")===e||(ClientStorage.unsetByPrefix("icon_"),ClientStorage.set("icon_registry_cache_identifier",e)),this.fetchFromLocal(r).then(null,(()=>this.fetchFromRemote(c,r,o))))))}getIconRegistryCache(){return this.promiseCache.get("icon_registry_cache_identifier",(async e=>{const i=await new AjaxRequest(TYPO3.settings.ajaxUrls.icons_cache).get({signal:e});return await i.resolve()}))}fetchFromRemote(e,i,t){return this.promiseCache.get(i,(async t=>{const n=await new AjaxRequest(TYPO3.settings.ajaxUrls.icons).withQueryArguments({icon:JSON.stringify(e)}).get({signal:t}),s=await n.resolve();return!n.response.redirected&&s.startsWith("<span")&&s.includes("t3js-icon")&&s.includes('<span class="icon-markup">')&&ClientStorage.set("icon_"+i,s),s}),t)}fetchFromLocal(e){return ClientStorage.isset("icon_"+e)?Promise.resolve(ClientStorage.get("icon_"+e)):Promise.reject()}}let iconsObject;iconsObject||(iconsObject=new Icons,"undefined"!=typeof TYPO3&&(TYPO3.Icons=iconsObject));export default iconsObject;