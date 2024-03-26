.. include:: /Includes.rst.txt

.. _important-23178-1668719172:

==========================================================================================
Important: #23178 - New TYPO3_CONF_VARS option FE|pageNotFound_handling_accessdeniedheader
==========================================================================================

See :issue:`23178`

Description
===========

In order to send a correct HTTP header to the browser when access to a page is denied,
a new option TYPO3_CONF_VARS is introduced.

The option :php:`FE|pageNotFound_handling_accessdeniedheader` allows to configure the
header which defaults to :php:`HTTP/1.0 403 Access denied`.

.. index:: Frontend, LocalConfiguration
