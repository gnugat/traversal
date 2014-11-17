<?php

namespace Gnugat\Traversal;

class Traversal
{
    /**
     * @param  array  $array
     * @param  array  $keys
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function getIn(array $array, array $keys, $default = null)
    {
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

    /**
     * @param array    $array
     * @param array    $keys
     * @param callable $f
     *
     * @return array
     */
    public function updateIn(array $array, array $keys, $f /* , $args... */)
    {
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

    /**
     * @param array $array
     * @param array $keys
     * @param mixed $value
     *
     * @return array
     */
    public function assocIn(array $array, array $keys, $value)
    {
        if (!$keys) {
            return $array;
        }
        $current = &$array;
        foreach ($keys as $key) {

            if ($current === null || !is_array($current)) {
                $current = [];
            }

            $current = &$current[$key];
        }
        $current = $value;

        return $array;
    }
}
