<?php

/*
 * This file is part of the Traversal project.
 *
 * (c) Igor Wiedler <igor@wiedler.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\Gnugat\Traversal;

use Gnugat\Traversal\Traversal;
use PHPUnit_Framework_TestCase;

class TraversalTest extends PHPUnit_Framework_TestCase
{
    private $traversal;

    protected function setUp()
    {
        $this->traversal = new Traversal();
    }

    /** @dataProvider provideGetIn */
    public function testGetIn($expected, $array, $keys, $default = null)
    {
        $this->assertSame($expected, \Gnugat\Traversal\get_in($array, $keys, $default));
    }

    public function provideGetIn()
    {
        $single = array('key' => 'value');
        $nested = array('foo' => array('bar' => array('baz' => 'value')));
        $list   = array(array('name' => 'foo'));

        return array(
            array('value', $single, array('key'), 'default'),
            array(array('bar' => array('baz' => 'value')), $nested, array('foo'), 'default'),
            array(array('baz' => 'value'), $nested, array('foo', 'bar'), 'default'),
            array('value', $nested, array('foo', 'bar', 'baz'), 'default'),
            array('default', $nested, array('foo', 'bar', 'bang'), 'default'),
            array('default', $nested, array('non_existent'), 'default'),
            array(null, $nested, array('non_existent')),
            array($nested, $nested, array(), 'default'),
            array($nested, $nested, array()),
            array('foo', $list, array(0, 'name')),
            array(null, array('foo' => null), array('foo'), 'err'),
        );
    }

    /** @dataProvider provideUpdateIn */
    public function testUpdateIn($expected, $array, $keys, $fn, array $args = array())
    {
        $this->assertSame($expected, call_user_func_array(
            array($this->traversal, 'updateIn'),
            array_merge(array($array, $keys, $fn), $args)
        ));
    }

    public function provideUpdateIn()
    {
        $nested = array('foo' => array('bar' => array('baz' => 40)));
        $single = array('key' => 'value');

        $add = function ($a, $b) { return $a + $b; };
        $identity = function ($x) { return $x; };

        return array(
            array(array('foo' => array('bar' => array('baz' => 42))), $nested, array('foo', 'bar', 'baz'), $add, array(2)),
            array(array('foo' => array('bar' => array('baz' => 40))), $nested, array('foo', 'bar', 'baz'), $identity),
            array(array('foo' => array('bar' => array('baz' => 40))), $nested, array(), $identity),
            array(array('key' => 'value'), $single, array(), $identity),
            array(array('foo' => null), array('foo' => null), array('foo'), $identity),
        );
    }

    /**
     * @dataProvider provideInvalidUpdateIn
     * @expectedException InvalidArgumentException
     */
    public function testInvalidUpdateIn($expected, $array, $keys, $fn, array $args = array())
    {
        $this->assertSame($expected, call_user_func_array(
            array($this->traversal, 'updateIn'),
            array_merge(array($array, $keys, $fn), $args)
        ));
    }

    public function provideInvalidUpdateIn()
    {
        $nested = array('foo' => array('bar' => array('baz' => 40)));

        $add = function ($a, $b) { return $a + $b; };
        $identity = function ($x) { return $x; };

        return array(
            array(array('foo' => array('bar' => array('baz' => 40))), $nested, array('non_existent'), $identity),
            array(array('foo' => array('bar' => array('baz' => 40))), $nested, array('non', 'existent'), $identity),
        );
    }

    /** @dataProvider provideAssocIn */
    public function testAssocIn($expected, $array, $keys, $value)
    {
        $this->assertSame($expected, $this->traversal->assocIn($array, $keys, $value));
    }

    public function provideAssocIn()
    {
        $nested = array('foo' => array('bar' => array('baz' => 'value')));
        $single = array('key' => 'value');
        $empty  = array();

        return [
            array(array('foo' => array('bar' => array('baz' => 'new value'))), $nested, array('foo', 'bar', 'baz'), 'new value'),
            array(array('key' => 'value'), $single, array(), 'new value'),
            array(array('foo' => array('bar' => 'new value')), $empty, array('foo', 'bar'), 'new value'),
            array(array('foo' => 'new value'), array('foo' => null), array('foo'), 'new value'),
            array(array('foo' => array('bar' => 'new value')), array('foo' => null), array('foo', 'bar'), 'new value'),
        ];
    }
}
