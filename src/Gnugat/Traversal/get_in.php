<?php

/*
 * This file is part of the Traversal project.
 *
 * (c) Igor Wiedler <igor@wiedler.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Traversal;

/**
 * Retrieve value from a nested structure using a list of keys:
 *
 * ```php
 * $users = array(
 *     array('name' => 'Igor Wiedler'),
 *     array('name' => 'Jane Doe'),
 *     array('name' => 'Acme Inc'),
 * );
 *
 * $name = Gnugat\Traversal\get_in($users, array(1, 'name'));
 * //= 'Jane Doe'
 * ```
 *
 * Non existent keys return null:
 *
 * ```php
 * $data = array('foo' => 'bar'];
 *
 * $baz = Gnugat\Traversal\get_in($data, array('baz'));
 * //= null
 * ```
 * You can provide a default value that will be used instead of null:
 *
 * ```php
 * $data = array('foo' => 'bar');
 *
 * $baz = Gnugat\Traversal\get_in($data, array('baz'), 'qux');
 * //= 'qux'
 * ```
 *
 * @param array $array
 * @param array $keys
 * @param mixed $default
 *
 * @return mixed
 */
function get_in(array $array, array $keys, $default = null) {
    if (!$keys) {
        return $array;
    }
    // This is a micro-optimization, it is fast for non-nested keys, but fails for null values
    if (count($keys) === 1 && isset($array[$keys[0]])) {
        return $array[$keys[0]];
    }
    $current = $array;
    foreach ($keys as $key) {
        if ($current === null || !array_key_exists($key, $current)) {
            return $default;
        }
        $current = $current[$key];
    }

    return $current;
}
