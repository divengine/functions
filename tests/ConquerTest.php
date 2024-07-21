<?php

namespace divengine\tests;

use PHPUnit\Framework\TestCase;

class ConquerTest extends TestCase
{
	public function testConquerArrayOfNumbers()
	{
		$pieces = [1, 2, 3];
		$expected = 123;
		$compiled = \divengine\conquer($pieces);
		$this->assertEquals($expected, $compiled);
	}

	public function testConquerArrayOfStrings()
	{
		$pieces = ['a', 'b', 'c'];
		$expected = 'abc';
		$compiled = \divengine\conquer($pieces);
		$this->assertEquals($expected, $compiled);
	}

	public function testConquerArrayOfMixedTypes()
	{
		$pieces = [1, 'a', 2, 'b', 3, 'c'];
		$expected = '1a2b3c';
		$compiled = \divengine\conquer($pieces);
		$this->assertEquals($expected, $compiled);
	}

	public function testConquerArrayOfArrays()
	{
		$pieces = [[1, 2, 3], [4, 5, 6], [7, 8, 9]];
		$expected = [1, 2, 3, 4, 5, 6, 7, 8, 9];
		$compiled = \divengine\conquer($pieces, recursive: false);
		$this->assertEquals($expected, $compiled);
	}

	public function testConquerArrayOfArraysOfNumbers()
	{
		$pieces = [[1, 2, 3], [4, 5, 6], [7, 8, 9]];
		$expected = "123456789";
		$compiled = \divengine\conquer($pieces, recursive: true);
		$this->assertEquals($expected, $compiled);
	}

	public function testConquerArrayOfArraysOfStrings()
	{
		$pieces = [['a', 'b', 'c'], ['d', 'e', 'f'], ['g', 'h', 'i']];
		$expected = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i'];
		$compiled = \divengine\conquer($pieces, recursive: false);
		$this->assertEquals($expected, $compiled);
	}

	public function testConquerArrayOfArraysOfStringsRecursive()
	{
		$pieces = [['a', 'b', 'c'], ['d', 'e', 'f'], ['g', 'h', 'i']];
		$expected = 'abcdefghi';
		$compiled = \divengine\conquer($pieces, recursive: true);
		$this->assertEquals($expected, $compiled);
	}

	public function testConquerArrayOfArraysOfMixedTypes()
	{
		$pieces = [[1, 'a', 2, 'b', 3, 'c'], [4, 'd', 5, 'e', 6, 'f'], [7, 'g', 8, 'h', 9, 'i']];
		$expected = [1, 'a', 2, 'b', 3, 'c', 4, 'd', 5, 'e', 6, 'f', 7, 'g', 8, 'h', 9, 'i'];
		$compiled = \divengine\conquer($pieces, recursive: false);
		$this->assertEquals($expected, $compiled);
	}

	public function testConquerArrayOfArraysOfMixedTypesRecursive()
	{
		$pieces = [[1, 'a', 2, 'b', 3, 'c'], [4, 'd', 5, 'e', 6, 'f'], [7, 'g', 8, 'h', 9, 'i']];
		$expected = '1a2b3c4d5e6f7g8h9i';
		$compiled = \divengine\conquer($pieces, recursive: true);
		$this->assertEquals($expected, $compiled);
	}
}
