<?php

namespace Gnugat\Traversal;

class Traversal
{
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
