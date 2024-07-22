<?php

namespace divengine\tests\Stuff;

class Person
{
	public int $id;
	public string $name;
	public string $username;

	/** @var array<\divengine\tests\Stuff\Address> */
	public array $addresses;
}
