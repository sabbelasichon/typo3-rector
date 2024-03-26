
.. include:: /Includes.rst.txt

.. _breaking-77209:

===============================================
Breaking: #77209 - Adapt default RECORDS tables
===============================================

See :issue:`77209`

Description
===========

The value of the constant :typoscript:`styles.content.shortcut.tables` which is set by
EXT:fluid_styled_content has been changed from :typoscript:`tt_content,tt_address,tt_news,tx_news_domain_model_news` to :typoscript:`tt_content`.


Impact
======

Shortcuts to records of the tables tt_address, tt_news and tx_news_domain_model_news don't work anymore.


Affected Installations
======================

Installations which use shortcut to records of the tables tt_address, tt_news and tx_news_domain_model_news


Migration
=========

Adopt the TS and use :typoscript:`styles.content.shortcut.tables := addToList(tt_address,tt_news,tx_news_domain_model_news)`

.. index:: TypoScript, Frontend
