<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 project.
 *
 * It is free software; you can redistribute it and/or modify it under the terms
 * of the MIT License (MIT). For the full copyright and license information,
 * please read the LICENSE file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\HtmlSanitizer\Builder;

use TYPO3\HtmlSanitizer\Behavior;
use TYPO3\HtmlSanitizer\Behavior\Attr\UriAttrValueBuilder;
use TYPO3\HtmlSanitizer\Sanitizer;
use TYPO3\HtmlSanitizer\Visitor\CommonVisitor;

/**
 * Builder, creating a `Sanitizer` instance with "common"
 * behavior for tags, attributes and values.
 */
class CommonBuilder implements BuilderInterface
{
    /**
     * @var Behavior\Attr[]
     */
    protected $globalAttrs;

    /**
     * @var Behavior\Attr
     */
    protected $hrefAttr;

    /**
     * @var Behavior\Attr
     */
    protected $srcAttr;

    /**
     * @var Behavior\Attr
     * @deprecated not used anymore
     */
    protected $srcsetAttr;

    public function __construct()
    {
        $bluntUriAttrValueBuilder = new UriAttrValueBuilder();
        $uriAttrValueBuilders = $this->createUriAttrValueBuilders();

        $this->globalAttrs = $this->createGlobalAttrs();

        $this->hrefAttr = (new Behavior\Attr('href'))
            ->addValues(...($uriAttrValueBuilders['href'] ?? $bluntUriAttrValueBuilder)->getValues());
        $this->srcAttr = (new Behavior\Attr('src'))
            ->addValues(...($uriAttrValueBuilders['src'] ?? $bluntUriAttrValueBuilder)->getValues());

        // @deprecated not used anymore
        $srcsetAttrValueBuilder = (new UriAttrValueBuilder())
            ->allowLocal(true)
            ->allowSchemes('http', 'https');
        $this->srcsetAttr = (new Behavior\Attr('src'))
            ->addValues(...$srcsetAttrValueBuilder->getValues());
    }

    public function build(): Sanitizer
    {
        $behavior = $this->createBehavior();
        $visitor = new CommonVisitor($behavior);
        return new Sanitizer($behavior, $visitor);
    }

    protected function createBehavior(): Behavior
    {
        return (new Behavior())
            ->withFlags(
                Behavior::ENCODE_INVALID_TAG
                | Behavior::REMOVE_UNEXPECTED_CHILDREN
                | Behavior::ENCODE_INVALID_PROCESSING_INSTRUCTION
            )
            ->withName('common')
            ->withTags(...array_values($this->createBasicTags()))
            ->withTags(...array_values($this->createMediaTags()))
            ->withTags(...array_values($this->createTableTags()));
    }

    protected function createBasicTags(): array
    {
        $names = [
            // https://developer.mozilla.org/en-US/docs/Web/HTML/Element#content_sectioning
            'address', 'article', 'aside', 'footer', 'header',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'main', 'nav', 'section',
            // https://developer.mozilla.org/en-US/docs/Web/HTML/Element#text_content
            'blockquote', 'dd', 'div', 'dl', 'dt', 'figcaption', 'figure', 'li', 'ol', 'p', 'pre', 'ul',
            // https://developer.mozilla.org/en-US/docs/Web/HTML/Element#inline_text_semantics
            'a', 'abbr',  'b', 'bdi', 'bdo', 'cite', 'code', 'data', 'dfn', 'em', 'i', 'kbd', 'mark',
            'q', 'rb', 'rp', 'rt', 'rtc', 'ruby', 's', 'samp', 'small', 'span', 'strong', 'sub', 'sup',
            'time', 'u', 'var', 'wbr',
            // https://developer.mozilla.org/en-US/docs/Web/HTML/Element#demarcating_edits
            'del', 'ins',
            // https://developer.mozilla.org/en-US/docs/Web/HTML/Element#forms
            'button', 'datalist', 'label', 'legend', 'meter', 'output', 'progress',
            // https://developer.mozilla.org/en-US/docs/Web/HTML/Element#interactive_elements
            'details', 'dialog', 'menu', 'summary',
            // https://developer.mozilla.org/en-US/docs/Web/HTML/Element#web_components
            // 'slot', 'template',
            // https://developer.mozilla.org/en-US/docs/Web/HTML/Element#obsolete_and_deprecated_elements
            'acronym', 'big', 'center', 'font', 'nobr', 'strike', 'tt',
        ];

        $tags = [];
        foreach ($names as $name) {
            $tags[$name] = (new Behavior\Tag($name, Behavior\Tag::ALLOW_CHILDREN))
                ->addAttrs(...$this->globalAttrs);
        }
        $tags['a']->addAttrs(
            $this->hrefAttr,
            ...$this->createAttrs(
                // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/a
                'download', 'hreflang', 'ping', 'rel', 'referrerpolicy', 'target', 'type',
                // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/a#deprecated_attributes
                'charset', 'name', 'rev'
            )
        );
        $tags['br'] = (new Behavior\Tag('br'))->addAttrs(...$this->globalAttrs);
        $tags['hr'] = (new Behavior\Tag('hr'))->addAttrs(...$this->globalAttrs);
        $tags['label']->addAttrs(...$this->createAttrs('for'));
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/li
        $tags['li']->addAttrs(...$this->createAttrs('value', 'type'));
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/meta
        $tags['meta'] = (new Behavior\Tag('meta', Behavior\Tag::PURGE_WITHOUT_ATTRS))
            ->addAttrs(...$this->globalAttrs)
            ->addAttrs((new Behavior\Attr('content'))->addValues(new Behavior\RegExpAttrValue('#^[\w]*$#')));
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/ol
        $tags['ol']->addAttrs(...$this->createAttrs('reversed', 'start', 'type'));
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/font
        $tags['font']->addAttrs(...$this->createAttrs('color', 'face', 'size'));

        return $tags;
    }

    protected function createMediaTags(): array
    {
        $tags = [];
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element#image_and_multimedia
        $tags['audio'] = (new Behavior\Tag('audio', Behavior\Tag::ALLOW_CHILDREN))
            ->addAttrs($this->srcAttr, ...$this->globalAttrs)
            ->addAttrs(...$this->createAttrs('autoplay', 'controls', 'loop', 'muted', 'preload'));
        $tags['video'] = (new Behavior\Tag('video', Behavior\Tag::ALLOW_CHILDREN))
            ->addAttrs($this->srcAttr, ...$this->globalAttrs)
            ->addAttrs(...$this->createAttrs('autoplay', 'controls', 'height', 'loop', 'muted', 'playsinline', 'poster', 'preload', 'width'));
        $tags['img'] = (new Behavior\Tag('img', Behavior\Tag::PURGE_WITHOUT_ATTRS))
            ->addAttrs($this->srcAttr, ...$this->globalAttrs)
            ->addAttrs(...$this->createAttrs('align', 'alt', 'border', 'decoding', 'fetchpriority', 'height', 'loading', 'name', 'sizes', 'srcset', 'width'));
        $tags['track'] = (new Behavior\Tag('track', Behavior\Tag::PURGE_WITHOUT_ATTRS))
            ->addAttrs($this->srcAttr, ...$this->globalAttrs)
            ->addAttrs(...$this->createAttrs('default', 'kind', 'label', 'srclang'));
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element#embedded_content
        $tags['picture'] = (new Behavior\Tag('picture', Behavior\Tag::ALLOW_CHILDREN))->addAttrs(...$this->globalAttrs);
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/source
        $tags['source'] = (new Behavior\Tag('source'))
            ->addAttrs(...$this->globalAttrs)
            ->addAttrs(...$this->createAttrs('media', 'sizes', 'src', 'srcset', 'type'));
        return $tags;
    }

    protected function createTableTags(): array
    {
        // // https://developer.mozilla.org/en-US/docs/Web/HTML/Element#table_content
        $tags = [];
        // declarations related to <table> elements
        $commonTableAttrs = $this->createAttrs('align', 'valign', 'bgcolor');
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/table
        $tags['table'] = (new Behavior\Tag('table', Behavior\Tag::ALLOW_CHILDREN))
            ->addAttrs(...$this->globalAttrs, ...$commonTableAttrs)
            ->addAttrs(...$this->createAttrs('border', 'cellpadding', 'cellspacing', 'summary'));
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/caption
        $tags['caption'] = (new Behavior\Tag('caption', Behavior\Tag::ALLOW_CHILDREN))
            ->addAttrs(...$this->globalAttrs)
            ->addAttrs(...$this->createAttrs('align'));
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/thead
        $tags['thead'] = (new Behavior\Tag('thead', Behavior\Tag::ALLOW_CHILDREN))
            ->addAttrs(...$this->globalAttrs, ...$commonTableAttrs);
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/tbody
        $tags['tbody'] = (new Behavior\Tag('tbody', Behavior\Tag::ALLOW_CHILDREN))
            ->addAttrs(...$this->globalAttrs, ...$commonTableAttrs);
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/tfoot
        $tags['tfoot'] = (new Behavior\Tag('tfoot', Behavior\Tag::ALLOW_CHILDREN))
            ->addAttrs(...$this->globalAttrs, ...$commonTableAttrs);
        $tags['tr'] = (new Behavior\Tag('tr', Behavior\Tag::ALLOW_CHILDREN))
            ->addAttrs(...$this->globalAttrs, ...$commonTableAttrs);
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/td
        $tags['td'] = (new Behavior\Tag('td', Behavior\Tag::ALLOW_CHILDREN))
            ->addAttrs(...$this->globalAttrs, ...$commonTableAttrs)
            ->addAttrs(...$this->createAttrs('abbr', 'axis', 'headers', 'colspan', 'rowspan', 'scope', 'width', 'height'));
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/th
        $tags['th'] = (new Behavior\Tag('th', Behavior\Tag::ALLOW_CHILDREN))
            ->addAttrs(...$this->globalAttrs, ...$commonTableAttrs)
            ->addAttrs(...$this->createAttrs('colspan', 'rowspan', 'scope'));
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/colgroup
        $tags['colgroup'] = (new Behavior\Tag('colgroup', Behavior\Tag::ALLOW_CHILDREN))
            ->addAttrs(...$this->globalAttrs, ...$commonTableAttrs)
            ->addAttrs(...$this->createAttrs('span'));
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/col
        $tags['col'] = (new Behavior\Tag('col')) // no children here
            ->addAttrs(...$this->globalAttrs, ...$commonTableAttrs)
            ->addAttrs(...$this->createAttrs('span', 'width'));
        return $tags;
    }

    /**
     * @return Behavior\Attr[]
     */
    protected function createGlobalAttrs(): array
    {
        // https://developer.mozilla.org/en-US/docs/Web/HTML/Global_attributes
        $attrs = $this->createAttrs(
            'class',
            'id',
            'dir',
            'lang',
            'nonce',
            'xml:lang',
            'itemid',
            'itemprop',
            'itemref',
            'itemscope',
            'itemtype',
            'role',
            'tabindex',
            'title',
            'translate'
        );
        $attrs[] = new Behavior\Attr('aria-', Behavior\Attr::NAME_PREFIX);
        $attrs[] = new Behavior\Attr('data-', Behavior\Attr::NAME_PREFIX);
        return $attrs;
    }

    /**
     * @return array<'href'|'src', UriAttrValueBuilder>
     */
    protected function createUriAttrValueBuilders(): array
    {
        return [
            'href' => (new UriAttrValueBuilder())
                ->allowLocal(true)
                ->allowSchemes('http', 'https', 'mailto', 'tel')
                // https://www.iana.org/assignments/uri-schemes/uri-schemes.xhtml
                // + addressing files
                ->allowSchemes('afp', 'file', 'ftp', 'git', 'nfs', 's3', 'sftp', 'smb', 'svn')
                // + addressing resources
                ->allowSchemes('geo', 'maps', 'news', 'snews', 'spotify', 'vnc', 'webcal')
                // + addressing applications
                ->allowSchemes('facetime', 'irc', 'ircs', 'jabber', 'skype', 'slack', 'sms', 'xmpp')
                // emails, see https://datatracker.ietf.org/doc/html/rfc2392
                ->allowSchemes('mid'),
            'src' => (new UriAttrValueBuilder())
                ->allowLocal(true)
                ->allowSchemes('http', 'https')
                // emails, see https://datatracker.ietf.org/doc/html/rfc2392
                ->allowSchemes('cid')
                ->allowDataMediaTypes('audio', 'image', 'video'),
        ];
    }

    /**
     * @param string ...$names
     * @return Behavior\Attr[]
     */
    protected function createAttrs(string ...$names): array
    {
        return array_map(
            function (string $name) {
                return new Behavior\Attr($name);
            },
            $names
        );
    }
}
