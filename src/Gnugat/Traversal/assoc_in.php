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
 * Set a value at a particular location:
 *
 * ```php
 * $data = array('foo' => 'bar');
 *
 * $new = Gnugat\Traversal\assoc_in($data, array('foo'), 'baz');
 * //= array('foo' => 'baz')
 * ```
 *
 * It will also set the value if it does not exist yet:
 *
 * ```php
 * $data = [];
 *
 * $new = Gnugat\Traversal\assoc_in($data, array('foo', 'bar'), 'baz');
 * //= array('foo' => array('bar' => 'baz'))
 * ```
 *
 * @param array $array
 * @param array $keys
 * @param mixed $value
 *
 * @return array
 */
function assoc_in(array $array, array $keys, $value) {
    if (!$keys) {
        return $array;
    }
    $current = &$array;
    foreach ($keys as $key) {
        if ($current === null || !is_array($current)) {
            $current = array();
        }
        $current = &$current[$key];
    }
    $current = $value;

    return $array;
}
