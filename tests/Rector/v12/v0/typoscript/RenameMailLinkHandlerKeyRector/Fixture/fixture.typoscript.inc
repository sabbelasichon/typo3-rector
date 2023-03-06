TCEMAIN.linkHandler {
    page {
        handler = TYPO3\\CMS\\Recordlist\\LinkHandler\\PageLinkHandler
        label = LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf:page
    }
    file {
        handler = TYPO3\\CMS\\Recordlist\\LinkHandler\\FileLinkHandler
        label = LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf:file
        displayAfter = page
        scanAfter = page
    }
    folder {
        handler = TYPO3\\CMS\\Recordlist\\LinkHandler\\FolderLinkHandler
        label = LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf:folder
        displayAfter = page,file
        scanAfter = page,file
    }
    url {
        handler = TYPO3\\CMS\\Recordlist\\LinkHandler\\UrlLinkHandler
        label = LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf:extUrl
        displayAfter = page,file,folder
        scanAfter = telephone
    }
    mail {
        handler = TYPO3\\CMS\\Recordlist\\LinkHandler\\MailLinkHandler
        label = LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf:email
        displayAfter = page,file,folder,url
        scanBefore = url
    }
    telephone {
        handler = TYPO3\\CMS\\Recordlist\\LinkHandler\\TelephoneLinkHandler
        label = LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf:telephone
        displayAfter = page,file,folder,url,mail
        scanBefore = url
    }
}
-----
TCEMAIN.linkHandler {
    page {
        handler = TYPO3\\CMS\\Recordlist\\LinkHandler\\PageLinkHandler
        label = LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf:page
    }
    file {
        handler = TYPO3\\CMS\\Recordlist\\LinkHandler\\FileLinkHandler
        label = LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf:file
        displayAfter = page
        scanAfter = page
    }
    folder {
        handler = TYPO3\\CMS\\Recordlist\\LinkHandler\\FolderLinkHandler
        label = LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf:folder
        displayAfter = page,file
        scanAfter = page,file
    }
    url {
        handler = TYPO3\\CMS\\Recordlist\\LinkHandler\\UrlLinkHandler
        label = LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf:extUrl
        displayAfter = page,file,folder
        scanAfter = telephone
    }
    email {
        handler = TYPO3\\CMS\\Recordlist\\LinkHandler\\MailLinkHandler
        label = LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf:email
        displayAfter = page,file,folder,url
        scanBefore = url
    }
    telephone {
        handler = TYPO3\\CMS\\Recordlist\\LinkHandler\\TelephoneLinkHandler
        label = LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf:telephone
        displayAfter = page,file,folder,url,mail
        scanBefore = url
    }
}
