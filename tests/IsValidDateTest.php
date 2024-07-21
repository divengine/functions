<?php

namespace divengine\tests;

use PHPUnit\Framework\TestCase;

class IsValidDateTest extends TestCase
{
    /**
     * Test valid date in default format.
     */
    public function testValidDateDefaultFormat()
    {
        $this->assertTrue(\divengine\is_valid_date('2023-07-20'));
    }

    /**
     * Test invalid date in default format.
     */
    public function testInvalidDateDefaultFormat()
    {
        $this->assertFalse(\divengine\is_valid_date('2023-02-30'));
    }

    /**
     * Test valid date with custom format.
     */
    public function testValidDateCustomFormat()
    {
        $this->assertTrue(\divengine\is_valid_date('20-07-2023', 'd-m-Y'));
    }

    /**
     * Test invalid date with custom format.
     */
    public function testInvalidDateCustomFormat()
    {
        $this->assertFalse(\divengine\is_valid_date('31-02-2023', 'd-m-Y'));
    }

    /**
     * Test with non-date string.
     */
    public function testWithStringInput()
    {
        $this->assertFalse(\divengine\is_valid_date('not a date'));
    }

    /**
     * Test with empty string.
     */
    public function testWithEmptyString()
    {
        $this->assertFalse(\divengine\is_valid_date(''));
    }

    /**
     * Test with valid leap year date.
     */
    public function testValidLeapYearDate()
    {
        $this->assertTrue(\divengine\is_valid_date('2024-02-29'));
    }

    /**
     * Test with invalid leap year date.
     */
    public function testInvalidLeapYearDate()
    {
        $this->assertFalse(\divengine\is_valid_date('2023-02-29'));
    }
}
