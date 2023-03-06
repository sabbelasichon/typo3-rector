page.10 = TEXT
page.10 {
    data = TSFE:fileTarget
}

page.20 < page.10
page.20 {
    data = TSFE:spamProtectEmailAddresses
}

page.30 = TEXT
page.30 {
    data = TSFE:intTarget
}

page.40 = TEXT
page.40 {
    data = TSFE:extTarget
}

page.50 = TEXT
page.50 {
    data = TSFE:baseUrl
}

page.60 = TEXT
page.60 {
    data = TSFE:foo
}
-----
page.10 = TEXT
page.10 {
    data = TSFE:config|config|fileTarget
}

page.20 < page.10
page.20 {
    data = TSFE:config|config|spamProtectEmailAddresses
}

page.30 = TEXT
page.30 {
    data = TSFE:config|config|intTarget
}

page.40 = TEXT
page.40 {
    data = TSFE:config|config|extTarget
}

page.50 = TEXT
page.50 {
    data = TSFE:config|config|baseUrl
}

page.60 = TEXT
page.60 {
    data = TSFE:foo
}
