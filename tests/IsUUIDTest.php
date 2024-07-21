<?php

namespace divengine\tests;

use PHPUnit\Framework\TestCase;

class IsUUIDTest extends TestCase
{
    /**
     * Test a valid UUID.
     */
    public function testValidUUID()
    {
        $this->assertTrue(\divengine\is_uuid('123e4567-e89b-12d3-a456-426614174000'));
    }

    /**
     * Test a valid UUID in uppercase.
     */
    public function testValidUUIDUppercase()
    {
        $this->assertTrue(\divengine\is_uuid('123E4567-E89B-12D3-A456-426614174000'));
    }

    /**
     * Test an invalid UUID with wrong characters.
     */
    public function testInvalidUUIDWrongCharacters()
    {
        $this->assertFalse(\divengine\is_uuid('123e4567-e89b-12d3-a456-zzzzzz174000'));
    }

    /**
     * Test an invalid UUID with wrong length.
     */
    public function testInvalidUUIDWrongLength()
    {
        $this->assertFalse(\divengine\is_uuid('123e4567-e89b-12d3-a456-42661417400'));
    }

    /**
     * Test an invalid UUID with missing sections.
     */
    public function testInvalidUUIDMissingSections()
    {
        $this->assertFalse(\divengine\is_uuid('123e4567e89b12d3a456426614174000'));
    }

    /**
     * Test an invalid UUID with extra characters.
     */
    public function testInvalidUUIDExtraCharacters()
    {
        $this->assertFalse(\divengine\is_uuid('123e4567-e89b-12d3-a456-4266141740001234'));
    }

    /**
     * Test with empty string.
     */
    public function testWithEmptyString()
    {
        $this->assertFalse(\divengine\is_uuid(''));
    }

    /**
     * Test with non-UUID string.
     */
    public function testWithNonUUIDString()
    {
        $this->assertFalse(\divengine\is_uuid('Hello, world!'));
    }
}
