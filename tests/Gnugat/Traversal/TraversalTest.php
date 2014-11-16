<?php

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
        $this->assertSame($expected, $this->traversal->getIn($array, $keys, $default));
    }

    public function provideGetIn()
    {
        $single = ['key' => 'value'];
        $nested = ['foo' => ['bar' => ['baz' => 'value']]];
        $list   = [['name' => 'foo']];

        return [
            ['value', $single, ['key'], 'default'],
            [['bar' => ['baz' => 'value']], $nested, ['foo'], 'default'],
            [['baz' => 'value'], $nested, ['foo', 'bar'], 'default'],
            ['value', $nested, ['foo', 'bar', 'baz'], 'default'],
            ['default', $nested, ['foo', 'bar', 'bang'], 'default'],
            ['default', $nested, ['non_existent'], 'default'],
            [null, $nested, ['non_existent']],
            [$nested, $nested, [], 'default'],
            [$nested, $nested, []],
            ['foo', $list, [0, 'name']],
            [null, ['foo' => null], ['foo'], 'err'],
        ];
    }

    /** @dataProvider provideUpdateIn */
    public function testUpdateIn($expected, $array, $keys, $fn, array $args = [])
    {
        $this->assertSame($expected, call_user_func_array(
            array($this->traversal, 'updateIn'),
            array_merge([$array, $keys, $fn], $args)
        ));
    }

    public function provideUpdateIn()
    {
        $nested = ['foo' => ['bar' => ['baz' => 40]]];
        $single = ['key' => 'value'];

        $add = function ($a, $b) { return $a + $b; };
        $identity = function ($x) { return $x; };

        return [
            [['foo' => ['bar' => ['baz' => 42]]], $nested, ['foo', 'bar', 'baz'], $add, [2]],
            [['foo' => ['bar' => ['baz' => 40]]], $nested, ['foo', 'bar', 'baz'], $identity],
            [['foo' => ['bar' => ['baz' => 40]]], $nested, [], $identity],
            [['key' => 'value'], $single, [], $identity],
            [['foo' => null], ['foo' => null], ['foo'], $identity],
        ];
    }

    /**
     * @dataProvider provideInvalidUpdateIn
     * @expectedException InvalidArgumentException
     */
    public function testInvalidUpdateIn($expected, $array, $keys, $fn, array $args = [])
    {
        $this->assertSame($expected, call_user_func_array(
            array($this->traversal, 'updateIn'),
            array_merge([$array, $keys, $fn], $args)
        ));
    }

    public function provideInvalidUpdateIn()
    {
        $nested = ['foo' => ['bar' => ['baz' => 40]]];

        $add = function ($a, $b) { return $a + $b; };
        $identity = function ($x) { return $x; };

        return [
            [['foo' => ['bar' => ['baz' => 40]]], $nested, ['non_existent'], $identity],
            [['foo' => ['bar' => ['baz' => 40]]], $nested, ['non', 'existent'], $identity],
        ];
    }

    /** @dataProvider provideAssocIn */
    public function testAssocIn($expected, $array, $keys, $value)
    {
        $this->assertSame($expected, $this->traversal->assocIn($array, $keys, $value));
    }

    public function provideAssocIn()
    {
        $nested = ['foo' => ['bar' => ['baz' => 'value']]];
        $single = ['key' => 'value'];
        $empty  = [];

        return [
            [['foo' => ['bar' => ['baz' => 'new value']]], $nested, ['foo', 'bar', 'baz'], 'new value'],
            [['key' => 'value'], $single, [], 'new value'],
            [['foo' => ['bar' => 'new value']], $empty, ['foo', 'bar'], 'new value'],
            [['foo' => 'new value'], ['foo' => null], ['foo'], 'new value'],
            [['foo' => ['bar' => 'new value']], ['foo' => null], ['foo', 'bar'], 'new value'],
        ];
    }
}
