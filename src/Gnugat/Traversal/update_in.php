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
 * Apply a function to the value at a particular location in a nested structure:
 *
 * ```php
 * $data = ['foo' => ['answer' => 42]];
 * $inc = function ($x) {
 *     return $x + 1;
 * };
 *
 * $new = Gnugat\Traversal\update_in($data, ['foo', 'answer'], $inc);
 * //= ['foo' => ['answer' => 43]]
 * ```
 *
 * You can variadically provide additional arguments for the function:
 *
 * ```php
 * $data = ['foo' => 'bar'];
 * $concat = function () { // Put arguments in here
 *     return implode('', func_get_args());
 * };
 *
 * $new = Gnugat\Traversal\update_in($data, ['foo'], $concat, ' is the ', 'best');
 * //= ['foo' => 'bar is the best']
 *
 * @param array    $array
 * @param array    $keys
 * @param callable $f
 *
 * @return array
 */
function update_in(array $array, array $keys, $f /* , $args... */) {
    $args = array_slice(func_get_args(), 3);
    if (!$keys) {
        return $array;
    }
    $current = &$array;
    foreach ($keys as $key) {
        if ($current === null || !array_key_exists($key, $current)) {
            throw new \InvalidArgumentException(sprintf('Did not find path %s in structure %s', json_encode($keys), json_encode($array)));
        }
        $current = &$current[$key];
    }
    $current = call_user_func_array($f, array_merge(array($current), $args));

    return $array;
}
