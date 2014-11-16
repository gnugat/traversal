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
$baz = $traversal->getIn($data, ['foo', 'bar', 'baz']);
```

> **Note**: `Traversal` is a fork of [Igor](https://igor.io/)'s [get-in](https://github.com/igorw/get-in).
> It provides support for PHP 5.3 and uses an Object Oriented approach instead of
> a functional one: you can inject it in your services as a dependency and mock
> it easily.

[![Travis CI](https://travis-ci.org/gnugat/traversal.png)](https://travis-ci.org/gnugat/traversal)

## Installation

Use [Composer](http://getcomposer.org/) to install Traversal in your projects:

    composer require gnugat/traversal:~1.0

## Usage

Instanciating `Traversal` is very easy:

```php
<?php

require __DIR__.'/vendor/autoload.php';

$traversal = new Traversal();
```

### Get in

Retrieve value from a nested structure using a list of keys:

```php
$users = [
    ['name' => 'Igor Wiedler'],
    ['name' => 'Jane Doe'],
    ['name' => 'Acme Inc'],
];

$name = $traversal->getIn($users, [1, 'name']);
//= 'Jane Doe'
```

Non existent keys return null:

```php
$data = ['foo' => 'bar'];

$baz = $traversal->getIn($data, ['baz']);
//= null
```
You can provide a default value that will be used instead of null:

```php
$data = ['foo' => 'bar'];

$baz = $traversal->getIn($data, ['baz'], 'qux');
//= 'qux'
```
### Update in

Apply a function to the value at a particular location in a nested structure:

```php
$data = ['foo' => ['answer' => 42]];
$inc = function ($x) {
    return $x + 1;
};

$new = $traversal->updateIn($data, ['foo', 'answer'], $inc);
//= ['foo' => ['answer' => 43]]
```

You can variadically provide additional arguments for the function:

```php
$data = ['foo' => 'bar'];
$concat = function (/* $args... */) {
    return implode('', func_get_args());
};

$new = $traversal->updateIn($data, ['foo'], $concat, ' is the ', 'best');
//= ['foo' => 'bar is the best']
```

### Assoc in

Set a value at a particular location:

```php
$data = ['foo' => 'bar'];

$new = $traversal->assocIn($data, ['foo'], 'baz');
//= ['foo' => 'baz']
```

It will also set the value if it does not exist yet:

```php
$data = [];

$new = $traversal->assocIn($data, ['foo', 'bar'], 'baz');
//= ['foo' => ['bar' => 'baz']]
```

## Inspiration

The naming and implementation is inspired by the `get-in`, `update-in` and
`assoc-in` functions from [clojure](http://clojure.org).
