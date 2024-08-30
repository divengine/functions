<?php

namespace divengine\tests;

use PHPUnit\Framework\TestCase;

/**
 * Tests for array_splice_assoc function
 */
class ArraySpliceAssocTest extends TestCase
{
	public function testSpliceNumeric()
	{

		$pieces = [1, 2, 3];
		$expected = [0 => 1, 3 => 4, 1 => 2, 2 => 3];
		$pieces = \divengine\array_splice_assoc($pieces, 1, 3, 0, 4);
		$this->assertEquals($expected, $pieces);

		$expected = [1,2,3,4];
		$this->assertEquals($expected, $pieces);
	}
}