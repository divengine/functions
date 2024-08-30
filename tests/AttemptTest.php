<?php


namespace divengine\tests;

use PHPUnit\Framework\TestCase;

/**
 * Tests for attempt function
 */
class AttemptTest extends TestCase
{
	public function testAttempt()
	{
		$result = \divengine\attempt(function() {
			return 1;
		});

		$this->assertEquals(1, $result);
	}

	public function testAttemptWithException()
	{
		$result = \divengine\attempt(function() {
			throw new \Exception('Test exception');
		});

		// assert instance of exception
		$this->assertInstanceOf(\Throwable::class, $result);
	}

	public function testDivisionByZero()
	{
		$division = function($a, $b) {
			return $a / $b;
		};

		$result = \divengine\attempt(fn() => $division(1, 0));

		// assert instance of exception
		$this->assertInstanceOf(\Throwable::class, $result);
	}
}