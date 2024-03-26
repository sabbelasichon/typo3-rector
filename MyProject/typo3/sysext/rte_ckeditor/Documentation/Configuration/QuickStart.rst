.. include:: /Includes.rst.txt


.. _config-quickstart:

========================
Configuration Quickstart
========================

Here we explain, how to modify the existing configuration in a few simple steps.

View Existing Configuration
===========================

To familiarize yourself with the configuration, look at the existing configuration
in your TYPO3 website:

To view the existing RTE presets in the "Global Configuration", go to
:guilabel:`System > Configuration` in the backend, choose
:guilabel:`$GLOBALS['TYPO3_CONF_VARS'] (Global Configuration)` and select
:guilabel:`RTE`:

.. figure:: images/global-configuration-rte.png
   :class: with-shadow

   Global Configuration: RTE > Presets

By default, TYPO3 is shipped with three configuration presets:

* default
* full
* minimal

Minimal Example
===============

Here is a very minimal example of changing the default configuration. All
configuration is done in a custom sitepackage extension, see also
:ref:`best-practice-sitepackage`.

Override the configuration preset "default" by adding this in :file:`<my_extension>/ext_localconf.php`
(replace `my_extension` with your extension key):

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:my_extension/Configuration/RTE/Default.yaml';

Add the file :file:`Configuration/RTE/Default.yaml` to your extension, use the file
:file:`EXT:rte_ckeditor/Configuration/RTE/Full.yaml` as example (see latest
`Full.yaml <https://github.com/typo3/typo3/blob/main/typo3/sysext/rte_ckeditor/Configuration/RTE/Full.yaml>`__)

We explain the example :file:`Minimal.yaml` from the Core:

.. code-block:: yaml
   :linenos:

   # Load default processing options
   imports:
       - { resource: 'EXT:rte_ckeditor/Configuration/RTE/Processing.yaml' }
       - { resource: 'EXT:rte_ckeditor/Configuration/RTE/Editor/Base.yaml' }

   # Minimal configuration for the editor
   editor:
     config:
       toolbar:
         items:
           - bold
           - italic
           - '|'
           - clipboard
           - undo
           - redo

line #2
   Imports existing files to make basic parts reusable and improve structure of configuration

line #9 toolbar
   See `toolbar <https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html>`__
