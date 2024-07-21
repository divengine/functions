<?php

namespace divengine\tests;

use PHPUnit\Framework\TestCase;

class IsClosureTest extends TestCase
{
	public function testIsClosure()
	{
		$expected = true;
		$compiled = \divengine\is_closure(fn () => null);
		$this->assertEquals($expected, $compiled);
	}

	public function testIsNotClosure()
	{
		$expected = false;
		$compiled = \divengine\is_closure(null);
		$this->assertEquals($expected, $compiled);
	}

	public function testIsNotClosureFunc()
	{
		$expected = true;
		$compiled = \divengine\is_not_closure('fn() => null');
		$this->assertEquals($expected, $compiled);
	}
}
