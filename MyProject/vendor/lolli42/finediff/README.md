[![Tests](https://github.com/lolli42/FineDiff/actions/workflows/tests.yml/badge.svg)](https://github.com/lolli42/FineDiff/actions/workflows/tests.yml)

FineDiff
========

FineDiff is a rather simple library to create HTML diff view of two strings:

```php
echo (new Diff())->render('hello world', 'hello2 worlds');
```
```
hello<ins>2</ins> world<ins>s</ins>
```

Installation
------------

```
$ composer req lolli42/finediff
```

Tags
----

* **1.x** Dropped PHP <7.2, has PHP >=7.2 support, added multibyte support, forces strict types, E_ALL error free
* **<1.x** is identical to cogpowered/finediff from ([https://github.com/cogpowered/FineDiff](https://github.com/cogpowered/FineDiff))

Usage
-----

Render the difference between two strings as HTML on a character basis:
```php
echo (new Diff())->render('string one', 'string two');
```
```
string <ins>tw</ins>o<del>ne</del>
```

Render the difference between two strings as HTML on a word basis:
```php
echo (new Diff(new Word()))->render('string one', 'string two');
```
```
string <del>one</del><ins>two</ins>
```

Special characters and entities are quoted by HTML renderer and multibyte strings are handled:
```php
echo (new Diff())->render('foo<bär>baz', 'foo<qüx>baz');
```
```
foo&lt;<del>b&auml;r</del><ins>q&uuml;x</ins>&gt;baz
```

Algorithm
---------

To create a diff-view between two string, an intermediate "Opcode" representation
is created that specifies the differences form string one to string two. The renderer
then takes this opcode and creates HTML from it. Note the opcode string is considered
internal and may change in a backwards breaking way with younger library releases.

```php
$diff = new Diff();
echo $diff->getOpcodes('hello world', 'hello2 worlds');
// c5i:2c6i:s
// Means: copy 5 chars "hello", then insert "2", then copy 6 chars " world", then insert "s"
echo $diff->process('hello wordl', 'c5i:2c6i:s');
// hello<ins>2</ins> world<ins>s</ins>
```

Running tests
-------------

Full test run:

```
$ composer update
$ vendor/bin/phpunit
```

Restricting to single files:

```
$ composer update
$ vendor/bin/phpunit tests/DiffTest.php
```

Casual setup to run tests with xdebug (3.x) enabled, an IDE like phpstorm should then break point:

```
$ composer update
$ XDEBUG_MODE="debug,develop" XDEBUG_TRIGGER="foo" vendor/bin/phpunit
```

History
-------

* Originally written by Raymond Hill ([https://github.com/gorhill/PHP-FineDiff](https://github.com/gorhill/PHP-FineDiff))
* Tweaked to bring it up to date with the modern world. That means documented, nicely formatted, tested code
  that can be easily extended by Rob Crowe ([https://github.com/cogpowered/FineDiff](https://github.com/cogpowered/FineDiff))
* Added PHP 8 compatibility and multibyte string support by Christian Kuhn ([https://github.com/lolli42/FineDiff](https://github.com/lolli42/FineDiff))

License
-------

MIT License. See LICENSE file.
