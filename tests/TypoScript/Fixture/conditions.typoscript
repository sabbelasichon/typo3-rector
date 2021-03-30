# Comment
[globalVar = GP:L = 2]
    lib.breadcrumb = TEXT
    lib.breadcrumb.value = Foo
[else]
    lib.breadcrumb = TEXT
    lib.breadcrumb.value = Baz
[global]
[globalVar = GP:L = 3]
    lib.breadcrumb = TEXT
    lib.breadcrumb.value = Bar
[global]
[applicationContext matches "/^Development/" || applicationContext == "Production/Staging"]
    lib.breadcrumb = TEXT
    lib.breadcrumb.value = Bar
[global]
-----
# Comment
[siteLanguage("languageId") == "2"]
    lib.breadcrumb = TEXT
    lib.breadcrumb.value = Foo
[else]
    lib.breadcrumb = TEXT
    lib.breadcrumb.value = Baz
[global]
[siteLanguage("languageId") == "3"]
    lib.breadcrumb = TEXT
    lib.breadcrumb.value = Bar
[global]
[applicationContext matches "/^Development/" || applicationContext == "Production/Staging"]
    lib.breadcrumb = TEXT
    lib.breadcrumb.value = Bar
[global]
