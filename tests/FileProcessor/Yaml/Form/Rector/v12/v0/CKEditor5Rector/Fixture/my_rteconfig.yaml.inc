imports:
    - { resource: "EXT:rte_ckeditor/Configuration/RTE/Processing.yaml" }
editor:
    externalPlugins:
        linebreak:
            resource: EXT:my_ext/Resources/Public/JavaScript/Backend/CKEditor/linebreak/linebreak.js
    config:
        autoParagraph: false
        contentsCss: ["/fileadmin/my_ext/assets/1/fonts.css", "/fileadmin/my_ext/assets/1/theme.css", "EXT:my_ext/Resources/Public/build/Stylesheets/rte.css"]
        stylesSet:
            - { name: "LLL:EXT:my_ext/Resources/Private/Language/Backend.xlf:rte.stylesSet.teaser_text", element: "p", attributes: { 'class': "teasertext"} }
            - { name: "LLL:EXT:my_ext/Resources/Private/Language/Backend.xlf:rte.stylesSet.info_text", element: "p", attributes: { 'class': "infotext"} }
            - { name: "LLL:EXT:my_ext/Resources/Private/Language/Backend.xlf:rte.stylesSet.notes", element: "p", attributes: { 'class': "notes"} }
            - { name: "LLL:EXT:my_ext/Resources/Private/Language/Backend.xlf:rte.stylesSet.list_with_points", element: "ul", attributes: { 'class': "list"} }
            - { name: "LLL:EXT:my_ext/Resources/Private/Language/Backend.xlf:rte.stylesSet.number_list", element: "ol", attributes: { 'class': "list"} }
            - { name: "LLL:EXT:my_ext/Resources/Private/Language/Backend.xlf:rte.stylesSet.headline_h1", element: ['h1', 'h2', 'h3', 'h4','h5', 'p'], attributes: { 'class': 'font--alpha' } }

        format_tags: "p;h1;h2;h3;h4;h5;pre"

        toolbar:
            - [ 'RemoveFormat' ]
            - [ 'Format', 'Styles' ]

            - [ 'Link', 'Unlink', 'Anchor', 'Table' ]
            - [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight' ]
            - [ 'Bold', 'Italic', 'Underline', 'Strike', 'Superscript', 'Subscript', 'Blockquote']
            - [ 'NumberedList', 'BulletedList', 'Indent' ]
            - [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ]
            - [ 'Undo', 'Redo', 'ShowBlocks', 'Source', 'Scayt' ]
            - [ 'WbrTag' ]

        extraPlugins:
            - justify
            - showblocks
            - linebreak

        justifyClasses:
            - font--align-left
            - font--align-center
            - font--align-right

        removePlugins:
            - image

        removeButtons:
            - Anchor

        extraAllowedContent: "*(*)[data-*]; span(shy)"

        allowTags:
            - wbr

        typo3link:
            additionalAttributes:
                - download

-----
imports:
    - { resource: "EXT:rte_ckeditor/Configuration/RTE/Processing.yaml" }
editor:
    externalPlugins:
        linebreak:
            resource: EXT:my_ext/Resources/Public/JavaScript/Backend/CKEditor/linebreak/linebreak.js
    config:
        autoParagraph: false
        contentsCss: ["/fileadmin/my_ext/assets/1/fonts.css", "/fileadmin/my_ext/assets/1/theme.css", "EXT:my_ext/Resources/Public/build/Stylesheets/rte.css"]
        style:
            definitions:
                - { name: "LLL:EXT:my_ext/Resources/Private/Language/Backend.xlf:rte.stylesSet.teaser_text", element: "p", attributes: { 'class': "teasertext"} }
                - { name: "LLL:EXT:my_ext/Resources/Private/Language/Backend.xlf:rte.stylesSet.info_text", element: "p", attributes: { 'class': "infotext"} }
                - { name: "LLL:EXT:my_ext/Resources/Private/Language/Backend.xlf:rte.stylesSet.notes", element: "p", attributes: { 'class': "notes"} }
                - { name: "LLL:EXT:my_ext/Resources/Private/Language/Backend.xlf:rte.stylesSet.list_with_points", element: "ul", attributes: { 'class': "list"} }
                - { name: "LLL:EXT:my_ext/Resources/Private/Language/Backend.xlf:rte.stylesSet.number_list", element: "ol", attributes: { 'class': "list"} }
                - { name: "LLL:EXT:my_ext/Resources/Private/Language/Backend.xlf:rte.stylesSet.headline_h1", element: ['h1', 'h2'], attributes: { 'class': 'font--alpha' } }

        # TODO: refactor this according to docs: "p;h1;h2;h3;h4;h5;pre"
        heading:
            options:
                - { model: 'paragraph', title: 'Paragraph' }

        toolbar:
            - RemoveFormat
            - '|'
            - Format
            - Styles
            - '|'
            - Link
            - Unlink
            - Anchor
            - Table
            - '|'
            - JustifyLeft
            - JustifyCenter
            - JustifyRight'
            - '|'
            - Bold
            - Italic
            - Underline
            - Strike
            - Superscript
            - Subscript
            - Blockquote
            - '|'
            - NumberedList
            - BulletedList
            - Indent
            - '|'
            - Cut
            - Copy
            - Paste
            - PasteText
            - PasteFromWord
            - '|'
            - Undo
            - Redo
            - ShowBlocks
            - Source
            - Scayt
            - '|'
            - WbrTag

        extraPlugins:
            - justify
            - showblocks
            - linebreak

        alignment:
            options:
                # TODO: this has to be refactored according to docs
                - { name: 'left', className: 'text-left' }
#                - font--align-left
#                - font--align-center
#                - font--align-right

        removePlugins:
            - image


        extraAllowedContent: "*(*)[data-*]; span(shy)"

        allowTags:
            - wbr

        typo3link:
            additionalAttributes:
                - download

