# Traversal

A service for hash map (assoc array) traversal.

When dealing with nested associative structures, traversing them can become
quite a pain. Mostly because of the amount of `isset` checking that is
necessary.

For example, to access a nested key `['foo']['bar']['baz']`, you must do
something like this:

```php
$baz = (isset($data['foo']['bar']['baz'])) ? $data['foo']['bar']['baz'] : null;
```

Enough already! `Traversal` provides a better way:

```php
$baz = Gnugat\Traversal\get_in($data, array('foo', 'bar', 'baz'));
```

> **Note**: `Traversal` is a fork of [Igor](https://igor.io/)'s [get-in](https://github.com/igorw/get-in).
> which provides support for PHP 5.3.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/31a73802-2b87-4d65-9ae1-52edaac9f2a6/mini.png)](https://insight.sensiolabs.com/projects/31a73802-2b87-4d65-9ae1-52edaac9f2a6)
[![Travis CI](https://travis-ci.org/gnugat/traversal.png)](https://travis-ci.org/gnugat/traversal)

## Installation

Use [Composer](http://getcomposer.org/) to install Traversal in your projects:

    composer require gnugat/traversal:~2.0

## Usage

Make the functions available by requiring Composer's autoloader:

```php
<?php

require __DIR__.'/vendor/autoload.php';
```

### Get in

Retrieve value from a nested structure using a list of keys:

```php
$users = array(
    array('name' => 'Igor Wiedler'),
    array('name' => 'Jane Doe'),
    array('name' => 'Acme Inc'),
);

$name = Gnugat\Traversal\get_in($users, array(1, 'name'));
//= 'Jane Doe'
```

Non existent keys return null:

```php
$data = array('foo' => 'bar'];

$baz = Gnugat\Traversal\get_in($data, array('baz'));
//= null
```
You can provide a default value that will be used instead of null:

```php
$data = array('foo' => 'bar');

$baz = Gnugat\Traversal\get_in($data, array('baz'), 'qux');
//= 'qux'
```

### Update in

Apply a function to the value at a particular location in a nested structure:

```php
$data = array('foo' => array('answer' => 42));
$inc = function ($x) {
    return $x + 1;
};

$new = Gnugat\Traversal\update_in($data, array('foo', 'answer'), $inc);
//= array('foo' => array('answer' => 43))
```

You can variadically provide additional arguments for the function:

```php
$data = array('foo' => 'bar');
$concat = function (/* $args... */) {
    return implode('', func_get_args());
};

$new = Gnugat\Traversal\update_in($data, array('foo'), $concat, ' is the ', 'best');
//= array('foo' => 'bar is the best')
```

### Assoc in

Set a value at a particular location:

```php
$data = array('foo' => 'bar');

$new = Gnugat\Traversal\assoc_in($data, array('foo'), 'baz');
//= array('foo' => 'baz')
```

It will also set the value if it does not exist yet:

```php
$data = [];

$new = Gnugat\Traversal\assoc_in($data, array('foo', 'bar'), 'baz');
//= array('foo' => array('bar' => 'baz'))
```

## Inspiration

The naming and implementation is inspired by the `get-in`, `update-in` and
`assoc-in` functions from [clojure](http://clojure.org).
