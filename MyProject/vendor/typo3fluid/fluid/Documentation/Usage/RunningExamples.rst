.. include:: /Includes.rst.txt

.. _running-examples:

================
Running Examples
================

The library source comes with a set of example scripts to study and play around with.
They are PHP entry scripts which render templates, their partials and layouts.

These files can be a starter to get an impression of Fluid general syntax and
capabilities, they're also linked within this documentation for single examples.
They can be run right away:

.. code-block:: bash

    $ git clone git@github.com:TYPO3/Fluid.git
    $ composer update
    # Run a single example file:
    $ php examples/example_format.php
    # Run all example files:
    $ find examples/ -maxdepth 1 -name \*.php -exec php {} \;
