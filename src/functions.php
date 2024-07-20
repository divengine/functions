<?php

/**
 * Div PHP Functions
 * 
 * A collection of standalone functions designed to enhance PHP capabilities,
 * providing common utilities without external dependencies. 
 * Part of the divengine* ecosystem, these functions offer atomic 
 * solutions that PHP lacks natively.
 * 
 * @package divengine/functions
 */

namespace divengine;

use Exception;
use DateTime;
use DateTimeZone;
use ArrayObject;
use stdClass;

#region Checkers

/**
 * Checks if the provided variable is a Closure.
 *
 * @param mixed $var The variable to check.
 * @return bool Returns true if the variable is a Closure, false otherwise.
 */
function is_closure(mixed $var): bool
{
	return $var instanceof \Closure;
}

/**
 * Checks if the provided variable is not a Closure.
 *
 * @param mixed $var The variable to check.
 * @return bool Returns true if the variable is not a Closure, false otherwise.
 */
function is_not_closure(mixed $var): bool
{
	return !is_closure($var);
}

/**
 * Validates whether the given string is a correctly formatted date according to the specified format.
 * This function checks not only if the date matches the format but also if it is a valid calendar date.
 *
 * @param string $date The date string to validate.
 * @param string $format The format to validate against, defaults to 'Y-m-d'.
 * @return bool Returns true if the string is a valid date according to the format and a real calendar date, otherwise false.
 */
function is_valid_date(string $date, string $format = 'Y-m-d'): bool
{
	$dateTime = DateTime::createFromFormat($format, $date);
	// Check if the date matches the specified format and if it represents a real date
	if ($dateTime && $dateTime->format($format) === $date) {
		// Extract parts of the date to verify if they represent a valid calendar date
		$year = (int) $dateTime->format('Y');
		$month = (int) $dateTime->format('m');
		$day = (int) $dateTime->format('d');

		// Use checkdate to ensure the date actually exists
		return checkdate($month, $day, $year);
	}
	return false;
}

/**
 * Checks if a string is a valid UUID (Universally Unique Identifier).
 * UUIDs are in the format of 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx' where 'x' is a hexadecimal digit.
 *
 * @param string $uuid The string to validate.
 * @return bool Returns true if the string is a valid UUID, otherwise false.
 */
function is_uuid(string $uuid): bool
{
	return preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $uuid) === 1;
}

/**
 * Checks if a string is a valid ISO 8601 date.
 *
 * The ISO 8601 date format includes patterns like "YYYY-MM-DDThh:mm:ss.sTZD".
 * This function validates a comprehensive pattern including:
 * - Complete date plus hours, minutes, seconds and a decimal fraction of a second
 * - Time zone designators (Z or +hh:mm or -hh:mm).
 *
 * @param string $date The date string to validate.
 * @return bool Returns true if the string is a valid ISO 8601 date, false otherwise.
 */
function is_ISO8601(string $date): bool
{
	$pattern = '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(\.\d+)?(Z|([+-])(\d{2}):(\d{2}))$/';
	return preg_match($pattern, $date) === 1;
}

/**
 * Checks if all elements in an array are valid UUIDs.
 *
 * @param array $uuids The array to validate.
 * @return bool Returns true if every element in the array is a valid UUID, otherwise false.
 */
function is_array_of_uuid(?array $uuids): bool
{
	if ($uuids === null || empty($uuids)) {
		return false;
	}

	foreach ($uuids as $uuid) {
		if (!is_uuid($uuid)) {
			return false;
		}
	}
	return true;
}

/**
 * Checks if a string is a valid email address according to the format defined in the FILTER_VALIDATE_EMAIL filter.
 *
 * @param string $email The email address to validate.
 * @return bool Returns true if the string is a valid email address, otherwise false.
 */
function is_email(string $email): bool
{
	return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Checks if a given string is a valid hexadecimal color in the form #FFFFFF.
 *
 * @param string $color The color string to validate.
 * @return bool Returns true if the string is a valid hex color, otherwise false.
 */
function is_hex_color(string $color): bool
{
	return preg_match('/^#([0-9A-F]{6}|[0-9A-F]{3})$/i', $color) === 1;
}

/**
 * Checks if the given string is a valid USDT (Tether) wallet address.
 * For TRON-based addresses, they typically start with 'T' followed by 33 alphanumeric characters.
 *
 * @param string $value The string to validate as a USDT address.
 * @return bool Returns true if the string is a valid USDT wallet address, otherwise false.
 */
function is_usdt(string $value): bool
{
	return preg_match('/^T[A-Za-z0-9]{33}$/', $value) === 1;
}

/**
 * Checks if a value can be interpreted as a boolean.
 * This function returns true if the value is either a boolean or a string that can be interpreted as a boolean ('true' or 'false', case-insensitive).
 *
 * @param mixed $value The value to evaluate.
 * @return bool Returns true if the value can be interpreted as a boolean, otherwise false.
 */
function is_boolean($value): bool
{
	if (is_bool($value)) {
		return true;
	}

	if (is_string($value)) {
		$normalizedValue = lower($value);
		return in_array($normalizedValue, ['true', 'false'], true);
	}

	return false;
}

/**
 * Checks if a given string is entirely in uppercase.
 * This function iterates through each character and checks if any are lowercase.
 * 
 * @param string $value The string to check.
 * @return bool Returns true if the string is entirely uppercase, otherwise returns false.
 */
function is_upper(string $value): bool
{
	return ctype_upper($value);
}

/**
 * Checks if a given string is entirely in lowercase.
 *
 * @param string $value The string to check.
 * @return bool Returns true if the string is entirely lowercase, otherwise returns false.
 */
function is_lower(string $value): bool
{
	return ctype_lower($value);
}

/**
 * Returns the input if it is not null or empty, otherwise returns null.
 *
 * @param mixed $value The array to check.
 * 
 * @return mixed Returns the input if not null or empty, otherwise null.
 */
function something_or_null($value): mixed
{
	return (is_null($value) || empty($value) && $value != '0' && $value != 0) ? null : $value;
}

#endregion

#region Converters
/**
 * Converts a given value to its boolean equivalent.
 *
 * @param mixed $value The value to be converted to boolean.
 * @return bool Returns the boolean equivalent of the input value.
 */
function boolean(mixed $value): bool
{
	if (is_bool($value)) {
		return $value;
	}

	if (is_string($value)) {
		$value = trim(lower($value));
	}

	if ($value === 'true' || $value === '1' || $value === 't' || $value === 1) {
		return true;
	}

	if ($value === 'false' || $value === '0' || $value === 'f') {
		return false;
	}

	// Catch-all conversion using PHP's native boolval function.
	return boolval($value);
}

/**
 * Converts a given value to its string equivalent.
 *
 * @param mixed $value The value to be converted to a string.
 * @param callable $criteria A callable that determines if the value should be converted.
 * 
 * @return string Returns the string equivalent of the input value.
 */
function string(mixed $value, callable|bool $criteria = null): string
{
	if ($criteria !== null) {
		if (is_callable($criteria)) {
			if (!$criteria($value)) {
				return null;
			}
		} else {
			if (!$criteria) {
				return null;
			}
		}
	}

	if (is_string($value)) {
		return $value;
	}

	if (is_numeric($value)) {
		return strval($value);
	}

	if (is_bool($value)) {
		return $value ? 'true' : 'false';
	}

	if (is_object($value) && method_exists($value, '__toString')) {
		return $value->__toString();
	}

	if (is_array($value) || is_object($value)) {
		return json_encode($value);
	}

	return '';
}

/**
 * Converts a given value to its string equivalent if not empty, otherwise returns null.
 *
 * @param mixed $value The value to be converted to a string or null if empty.
 * @return string|null Returns the string equivalent of the input value or null if it's empty.
 */
function string_nullable(mixed $value): ?string
{
	return string($value, !(empty($value) && $value !== '0' && $value !== 0));
}

#endregion

#region Strings

/**
 * Converts a given string to its uppercase equivalent.
 * Returns an empty string if the input is null.
 *
 * @param string|null $value The string to be converted to uppercase.
 * @return string The uppercase version of the input string, or an empty string if input is null.
 */
function upper(?string $value): string
{
	if ($value === null) {
		return '';
	}

	return mb_strtoupper(string($value));
}


/**
 * Converts a given string to its lowercase equivalent.
 * Returns an empty string if the input is null.
 *
 * @param string|null $value The string to be converted to lowercase.
 * @return string The lowercase version of the input string, or an empty string if input is null.
 */
function lower(?string $value): string
{
	if ($value === null) {
		return '';
	}

	return mb_strtolower($value);
}

/**
 * Converts a given string to its uppercase equivalent if it's not null.
 * Returns null if the input is null.
 *
 * @param string|null $value The string to be converted to uppercase or null.
 * @return string|null The uppercase version of the input string, or null if input is null.
 */
function upper_nullable(?string $value): ?string
{
	if ($value === null) {
		return null;
	}

	return mb_strtoupper($value);
}

/**
 * Converts a given string to its lowercase equivalent if it's not null.
 * Returns null if the input is null.
 *
 * @param string|null $value The string to be converted to lowercase or null.
 * @return string|null The lowercase version of the input string, or null if input is null.
 */
function lower_nullable(?string $value): ?string
{
	if ($value === null) {
		return null;
	}

	return mb_strtolower($value);
}
#endregion

#region Arrays
/**
 * Converts each element of an array to its string equivalent, 
 * using the 'string' function.
 * Returns null if the input array is null or empty.
 *
 * @param array|null $value The array to be converted to an array of strings, or null.
 * 
 * @return array|null An array with each element converted to a string, or null if input is null or empty.
 */
function string_array(?array $value): ?array
{
	if ($value === null || empty($value)) {
		return null;
	}

	$value = array_map(fn ($item) => string($item), $value);

	return $value;
}

/**
 * Converts each element of an array to its string equivalent 
 * if possible, otherwise sets it to null.
 * Returns null if the input array is null.
 *
 * @param array|null $value The array to be processed, where each element is converted to a string or set to null.
 * @return array|null An array where each element is a string or null, or null if input is null.
 */
function string_array_nullable(?array $value): ?array
{
	if ($value === null || empty($value)) {
		return null;
	}

	return array_map(function ($item) {
		try {
			return string_nullable($item);
		} catch (Exception $e) {
			return null;
		}
	}, $value);
}

/**
 * Returns an array of string elements from the input array, filtering out non-stringable elements.
 * Returns null if the resulting array is empty.
 *
 * @param array|null $value The array to be processed.
 * @return array|null An array of string elements, or null if the resulting array is empty.
 */
function array_filter_stringable(?array $value): ?array
{
	if ($value === null) {
		return null;
	}

	$filteredArray = array_filter($value, function ($item) {
		return is_scalar($item) || is_object($item) && method_exists($item, '__toString');
	});

	if (empty($filteredArray)) {
		return null;
	}

	return $filteredArray;
}


#endregion

#region Numeric

/**
 * Converts a given value to an integer and ensures it is not less than a specified minimum.
 *
 * @param mixed $value The value to be converted to an integer.
 * @param int $min The minimum value to return.
 * @return int The original value converted to an integer, or the minimum value if the original is less.
 */
function int_or_min($value, int $min): int
{
	$intValue = intval($value);

	return ($intValue < $min) ? $min : $intValue;
}

/**
 * Converts a given value to an integer and ensures it does not exceed a specified maximum.
 *
 * @param mixed $value The value to be converted to an integer.
 * @param int $max The maximum value to return.
 * @return int The original value converted to an integer, or the maximum value if the original is more.
 */
function int_or_max($value, int $max): int
{
	$intValue = intval($value);

	return ($intValue > $max) ? $max : $intValue;
}

/**
 * Converts a given value to an integer, or returns a default value if the input is empty or invalid.
 *
 * @param mixed $value The value to be converted.
 * @param int $default The default value to return if the input is empty or invalid.
 * @return int The integer value of the input, or the default value if the input is empty or invalid.
 */
function int_or_default($value, ?int $default = 0): int
{
	if (empty($value) && $value !== '0' && $value !== 0) {
		return $default;
	}

	return intval($value);
}

/**
 * Converts a given value to an integer, unless the value is empty, in which case it returns null.
 *
 * @param mixed $value The value to be converted to an integer.
 * @return int|null The integer value of the input, or null if the input is empty.
 */
function int_or_null($value): ?int
{
	return int_or_default($value, null);
}

/**
 * Returns the input value if it is non-zero; returns a default value if the value is zero.
 *
 * @param int $value The integer to check.
 * @param int $default The default value to return if the input is zero.
 * @return int Returns the input integer if it is non-zero, otherwise the default value.
 */
function non_zero_or_default(int $value, ?int $default = -1): int
{
	return ($value === 0) ? $default : $value;
}

/**
 * Returns the input value if it is non-zero; returns null if the value is zero.
 *
 * @param int $value The integer to check.
 * @return int|null Returns the input integer if it is non-zero, otherwise null.
 */
function non_zero_or_null(int $value): ?int
{
	return non_zero_or_default($value, null);
}

/**
 * Converts a given value to an integer or returns a default value if the input is non-numeric.
 *
 * @param mixed $value The value to be converted.
 * @param int $default The default integer to return if the input is non-numeric.
 * @return int Returns the integer equivalent if the input is numeric, otherwise the default value.
 */
function numeric_or_default($value, ?int $default = 0): int
{
	return is_numeric($value) ? intval($value) : $default;
}

/**
 * Converts a given value to an integer if it is numeric, otherwise returns null.
 *
 * @param mixed $value The value to be converted.
 * @return int|null Returns the integer equivalent if the input is numeric, otherwise null.
 */
function numeric_or_null($value): ?int
{
	return numeric_or_default($value, null);
}

/**
 * Converts a given value to a float, or returns a default value if the input is empty or non-numeric.
 *
 * @param mixed $value The value to be converted.
 * @param float $default The default float to return if the input is empty or non-numeric.
 * @return float Returns the float equivalent if the input is not empty, otherwise the default value.
 */
function float_or_default($value, ?float $default = 0.0): float
{
	if (empty($value) && $value !== '0' && $value !== 0 && $value !== 0.0) {
		return $default;
	}

	return floatval($value);
}

/**
 * Converts a given value to a float if it is not empty, otherwise returns null.
 *
 * @param mixed $value The value to be converted to float.
 * @return float|null Returns the float equivalent of the input if it is not empty, otherwise null.
 */
function float_or_null($value): ?float
{
	return float_or_default($value, null);
}
#endregion

#region Processors

/**
 * Replaces all occurrences of a search value with a replacement value in a given string.
 *
 * @param mixed $input The string to perform replacements on.
 * @param mixed $search The value to search for.
 * @param mixed $replace The replacement value.
 * @return string Returns the modified string with replacements.
 */
function str_safe_replace_sensitive(mixed $input, mixed $search, mixed $replace): string
{
	$search = string($search);
	$replace = string($replace);
	$input = string($input);

	return str_replace($search, $replace, $input);
}

/**
 * Replaces all occurrences of a search value with a replacement value in a given string, ignoring case.
 *
 * @param string $input The string to perform replacements on.
 * @param string $search The value to search for.
 * @param string $replace The replacement value.
 * @return string Returns the modified string with replacements.
 */
function str_safe_replace(mixed $input, mixed $search, mixed $replace): string
{
	$search = string($search);
	$replace = string($replace);
	$input = string($input);

	return str_ireplace($search, $replace, $input);
}

/**
 * Replaces all occurrences of a search value with a replacement value in an array.
 *
 * @param array $array The array to perform replacements on.
 * @param mixed $search The value to search for.
 * @param mixed $replace The replacement value.
 * @return array Returns the modified array with replacements.
 */
function array_replace_values(array $array, $search, $replace)
{
	foreach ($array as $key => $value) {
		$array[$key] = $value == $search ? $replace : $value;
	}

	return $array;
}

/**
 * Replaces all occurrences of a search value with a replacement value in an array, using strict comparison.
 *
 * @param array $array The array to perform replacements on.
 * @param mixed $search The value to search for.
 * @param mixed $replace The replacement value.
 * @return array Returns the modified array with replacements.
 */
function array_replace_values_strict(array $array, $search, $replace)
{
	foreach ($array as $key => $value) {
		$array[$key] = $value === $search ? $replace : $value;
	}

	return $array;
}
#endregion

/**
 * Checks if a string contains another string.
 * This function uses multibyte string position to accurately determine the presence of a substring.
 *
 * @param string|null $string The string to search within.
 * @param string|null $search The substring to search for.
 * @return bool Returns true if the substring is found, otherwise false.
 */
function contains_sensitive(?string $string, ?string $search): bool
{
	$string = $string ?? '';
	$search = $search ?? '';

	return mb_strpos($string, $search) !== false;
}

/**
 * Checks if a string contains another string, ignoring case.
 * This function uses a case-insensitive multibyte string position check.
 *
 * @param string|null $string The string to search within.
 * @param string|null $search The substring to search for.
 * @return bool Returns true if the substring is found, otherwise false.
 */
function contains(?string $string, ?string $search): bool
{
	$string = $string ?? '';
	$search = $search ?? '';

	return mb_stripos($string, $search) !== false;
}


/**
 * Converts a datetime string to a date string in the format 'Y-m-d'.
 *
 * @param string $datetime The datetime string to convert.
 * @return string The date in 'Y-m-d' format.
 */
function datetime_to_date(string $datetime): string
{
	try {
		$datetime = new DateTime($datetime);
		return $datetime->format('Y-m-d');
	} catch (Exception $e) {
		// Handle the exception if the datetime string is invalid
		return "Invalid datetime";
	}
}

/**
 * Returns the negated boolean value of the given expression.
 * This function is intended to improve readability in conditional statements by using natural language.
 *
 * @param mixed $value The value to negate.
 * @return bool Returns false if the value is truthy, true if the value is falsey.
 */
function false($value): bool
{
	return !boolean($value);
}

/**
 * Determines if a value evaluates to true in a boolean context.
 * 
 * This function is intended to improve readability in conditional statements by using natural language.
 * 
 * @param mixed $value The value to evaluate.
 * 
 * @return bool Returns true if the value is truthy, otherwise false.
 */
function true($value): bool
{
	return boolean($value);
}

/**
 * Alias for the 'false' function.
 * 
 * This function is intended to improve readability in conditional statements by using natural language.
 * 
 * @param mixed $value The value to evaluate.
 * 
 * @return bool Returns true if the value is falsey, otherwise false.

 */
function not($value): bool
{
	return false($value);
}

/**
 * Alias for the 'true' function.
 * 
 * @param mixed $value The value to evaluate.
 * 
 * @return bool Returns true if the value is truthy, otherwise false.
 */
function is_true($value): bool
{
	return true($value);
}

/**
 * Alias for the 'false' function.
 * 
 * @param mixed $value The value to evaluate.
 * 
 * @return bool Returns true if the value is falsey, otherwise false.
 */
function is_false($value): bool
{
	return !false($value);
}


/**
 * Check if a string is a valid url
 */
function is_url(string $url): bool
{
	return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

function url_nullable(?string $url): ?string
{
	$original = $url;

	if ($url === null) {
		return null;
	}

	if (!is_url($url)) {
		$url = 'http://' . $original;
	}

	if (is_url($url)) {
		return $url;
	}

	return null;
}

/**
 * Map an object or array of objects to another object or array of objects
 *
 * @param object|array $source
 * @param array|object $map
 * @param array<string> $onlyFields
 *
 * @return mixed
 */
function map(mixed $source, array|object $map): mixed
{
	if ($source === null) {
		$source = new stdClass();
	}

	if (is_array($source)) {
		return array_map(fn ($obj) => map($obj, $map), $source);
	}

	if (is_closure($map)) {
		return $map($source);
	}

	$newObject = new stdClass();

	if (is_object($map)) {
		$newObject = clone $map;
		$map = array_keys((array) $map);
	}

	foreach ($map as $key => $value) {
		if (is_int($key)) {
			$key = $value;
		}

		if (is_closure($value)) {
			$passValue = null;

			if (isset($source->$key)) {
				$passValue = $source?->$key;
			}

			$newObject->$key = $value($source, $passValue);
		} else if (is_string($value)) {
			if (property_exists($source, $value)) {
				$newObject->$key = $source->$value;
			} else {
				$newObject->$key = null;
			}
		} else {
			$newObject->$key = $value;
		}
	}
	return $newObject;
}

/**
 * Converts all elements of an input array to integers.
 * If the input is not an array, returns an empty array.
 *
 * @param mixed $array The array whose elements are to be converted to integers.
 * @return array An array with all elements converted to integers.
 */
function integer_array($array): array
{
	if (!is_array($array)) {
		return [];
	}

	return array_map('intval', $array);
}

/**
 * Splits a comma-separated string into an array of integers.
 * Strips out any spaces around the numbers and ignores any non-numeric entries, converting them to zero.
 *
 * @param string|null $string The comma-separated string of integers.
 * @return array An array of integers.
 */
function split_comma_separated_ints(?string $string): array
{
	if (empty($string)) {
		return [];
	}

	// Split the string by commas, trim spaces, and convert each part to an integer.
	return array_map('intval', array_map('trim', explode(',', $string)));
}

/**
 * Performs a safe division of two numbers.
 *
 * @param float $a The numerator.
 * @param float $b The denominator.
 * @return float|null The result of the division, or null if the denominator is zero.
 */
function division(float $a, float $b): ?float
{
	if ($b == 0) {
		return null;  // Return null to indicate an undefined result due to division by zero.
	}
	return $a / $b;
}

/**
 * Ensures that the given value is an object. Returns the object if it is, or null otherwise.
 *
 * @param mixed $value The value to check.
 * @return object|null The object, if the input is an object; otherwise, null.
 */
function object_or_null($value): ?object
{
	return is_object($value) ? $value : null;
}

/**
 * Cleans up and extracts a numeric phone number from a given string.
 * Removes spaces, dashes, pluses, and parentheses, and truncates to a specified length.
 *
 * @param string|null $value The input string containing the phone number.
 * @param int $length The maximum length of the numeric string to return, default is 10.
 * @return string|null Returns a string of digits up to the specified length, or null if input is null.
 */
function clean_phone_number(?string $value, int $length = 10): ?string
{
	if ($value === null) return null;

	// Remove all characters except digits
	$value = preg_replace('/\D/', '', $value);

	// Return the substring of the specified length
	return substr($value, 0, $length);
}

/**
 * Calculate the length of a value.
 *
 * This function calculates the length of a value based on its type.
 * If the value is empty or null, the length is considered 0.
 *
 * For numeric values, it calculates the number of digits 
 *
 * @param mixed $value The value for which to calculate the length.
 *
 * @return int The length of the value.
 */
function len($value): int
{
	if (empty($value) && $value !== 0) {
		return 0;
	}

	if (is_string($value)) {
		return mb_strlen($value, 'UTF-8');
	}

	if (is_array($value)) {
		return count($value);
	}

	if (is_numeric($value)) {
		return strlen("$value");
	}

	if (is_object($value)) {
		$a = new ArrayObject($value);
		return $a->count();
	}

	if (is_bool($value)) {
		return 1;
	}

	return 0;
}

function teaser($text, $limit)
{
	$text = strip_tags("$text");
	$text = preg_replace('/\s+/', ' ', $text);
	$text = trim($text);

	if (mb_strlen($text) <= $limit) {
		return $text;
	}

	$cutText = mb_substr($text, 0, $limit);
	$lastSpace = mb_strrpos($cutText, ' ');

	if ($lastSpace !== false) {
		$cutText = mb_substr($cutText, 0, $lastSpace);
	}

	$pattern = '/(http[s]?:\/\/[^\s]+|@[^\s]+|#[^\s]+)/u';
	preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);
	foreach ($matches[0] as $match) {
		$startPos = $match[1];
		$endPos = $startPos + mb_strlen($match[0]);

		if ($startPos < $limit && $endPos > $limit) {
			$cutText = mb_substr($text, 0, $startPos);
			break;
		}
	}

	return trim($cutText) . '...';
}

function teaser150($text)
{
	return teaser($text, 150);
}

function teaser200($text)
{
	return teaser($text, 200);
}

function teaser300($text)
{
	return teaser($text, 300);
}

function teaser500($text)
{
	return teaser($text, 500);
}

/**
 * Check if a value is in a string, numeric, array or object
 * 
 * @param mixed $needle
 * @param mixed $haystack
 * 
 * @return string
 */
function removeAccent(string $value, bool $includeNTilde = false): string
{
	$search = ['á', 'é', 'í', 'ó', 'ú', 'ü', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ü'];
	$replace = ['a', 'e', 'i', 'o', 'u', 'u', 'A', 'E', 'I', 'O', 'U', 'U'];

	if ($includeNTilde) {
		$search[] = 'ñ';
		$replace[] = 'n';
		$search[] = 'Ñ';
		$replace[] = 'N';
	}

	return strtr($value, array_combine($search, $replace));
}

/**
 * Split a value into an array
 *
 * @param mixed $value The value to split.
 *
 * @return array The array of characters.
 */
function divide(mixed $value): array
{
	return match (true) {
		is_null($value) => [],
		is_string($value) => mb_str_split($value),
		is_array($value) => $value,
		is_object($value) => (array) $value,
		is_numeric($value) => mb_str_split("$value"),
		is_bool($value) => $value ? [true] : [false],
		default => []
	};
}

function unite(array $elements)
{
}
/**
 * Check position of element in a string, numeric, array or object
 * 
 * @param mixed $needle
 * @param mixed $haystack
 * 
 * @return int|false
 */
function search(mixed $haystack, mixed $needle, int $offset = 0, ?string $encoding = null)
{
	if (empty($haystack) || empty($needle)) {
		return false;
	}

	if (is_numeric($haystack)) {
		$haystack = "$haystack";
	}

	if (is_string($haystack)) {
		return mb_strpos($haystack, $needle, $offset, $encoding);
	}

	if (is_object($haystack)) {
		$haystack = (array) $haystack;
	}

	if (is_array($haystack)) {
		$haystack = array_slice($haystack, $offset);
		$pos = array_search($needle, $haystack, true);
		return $pos === false ? false : $pos + $offset;
	}

	if (is_bool($haystack)) {
		return $haystack === $needle ? 0 : false;
	}

	return false;
}

/**
 * Pads a value to a certain length with another value.
 * Supports padding strings, numerics, and arrays. Returns the original value if padding cannot be applied.
 *
 * @param mixed $value The value to pad.
 * @param int $length The target length of the output.
 * @param mixed $padValue The value to pad with. Defaults to a single space.
 * @param int $padType Specifies which side to pad. Can be STR_PAD_RIGHT or STR_PAD_LEFT.
 * @return mixed Padded value or original value if padding is not applicable.
 */
function pad(mixed $value, int $length, mixed $padValue = ' ', int $padType = STR_PAD_RIGHT)
{
	if (empty($padValue) && $padValue !== 0 && $padValue !== '0') {
		return $value;
	}

	if (is_object($value) || is_bool($value)) {
		// Return the value as is if it's an object or boolean since padding these types isn't supported.
		return $value;
	}

	// Convert the value to an array
	$arr = is_array($value) ? $value : str_split((string) $value);
	$len = count($arr);

	if ($len >= $length) {
		return $value;
	}

	$padLength = $length - $len;
	$padArray = array_fill(0, $padLength, $padValue);

	if ($padType === STR_PAD_LEFT) {
		$arr = array_merge($padArray, $arr);
	} else {
		$arr = array_merge($arr, $padArray);
	}

	return match (true) {
		is_string($value) => implode('', $arr),
		is_numeric($value) => is_float($value + 0) ? floatval(implode('', $arr)) : intval(implode('', $arr)),
		default => $arr
	};
}


/**
 * Checks if all items in an array meet the specified required fields criteria.
 * Each item is validated to ensure it contains all fields specified in the required fields list.
 *
 * @param array $array The array of items to validate.
 * @param array $requiredFields The list of fields each item must contain.
 * @return bool Returns true if all items contain all required fields, otherwise false.
 */
function validate_required_fields_of_list(array $array, array $requiredFields): bool
{
	foreach ($array as $item) {
		if (!validate_required_fields_of_item($item, $requiredFields)) {
			return false;
		}
	}
	return true;
}

/**
 * Checks if an object has all required fields with valid values based on provided validators.
 * If no validator is provided for a field, it checks for the existence of the field.
 *
 * @param object $object The object to validate.
 * @param array $required_fields An associative array where keys are field names and values are validator functions.
 * @return bool Returns true if the object passes all validations, otherwise false.
 */
function validate_required_fields_of_item($object, array $required_fields): bool
{
	if (!is_object($object)) {
		return false; // Ensure that the input is an object.
	}

	foreach ($required_fields as $field => $validator) {
		if (is_numeric($field) && is_string($validator)) {
			// If the key is numeric and the value is a string, assume it's a field with a default validation function.
			$field = $validator;
			$validator = fn ($v) => true; // Default validator does nothing, just checks for field existence.
		}

		if (!property_exists($object, $field) || !$validator($object->$field)) {
			return false; // Check if the field exists and passes the validation.
		}
	}

	return true;
}

/**
 * Generates a new random hash. Mostly used for temporary or non-cryptographic purposes.
 *
 * @param string $algorithm The hashing algorithm to use ('md5', 'sha256', etc.). Default is 'md5'.
 * @return string Returns a hashed string using the specified algorithm.
 */
function random_hash($algorithm = 'md5')
{
	try {
		$bytes = random_bytes(16); // Generate 16 random bytes
		return hash($algorithm, $bytes);
	} catch (Exception $e) {
		// Handle potential errors when generating random bytes
		error_log("Error generating random hash: " . $e->getMessage());
		return null; // Return null or handle accordingly
	}
}

/**
 * Generates a secure random hash using a cryptographic-safe method.
 *
 * @return string Returns a securely generated random hash.
 */
function secure_random_hash()
{
	try {
		$bytes = random_bytes(32); // Generate 32 random bytes for more entropy
		return bin2hex($bytes); // Convert binary data to hexadecimal representation
	} catch (Exception $e) {
		// Handle potential errors when generating random bytes
		error_log("Error generating secure random hash: " . $e->getMessage());
		return null;
	}
}

/**
 * Performs a multibyte-safe trim operation to remove specified characters from both ends of a string.
 * If no characters are specified, whitespace will be removed by default.
 *
 * @param string $str The string to trim.
 * @param string|null $chars A string of characters to be removed. If null, whitespace is removed.
 * @return string The trimmed string.
 */
function trimer(string $str, ?string $chars = null): string
{
	if ($chars !== null) {
		// preg_quote escapes special characters for use in the regular expression.
		$chars = preg_quote($chars, '/');
		$pattern = "^[$chars]+|[$chars]+$";
	} else {
		// Default to trimming whitespace if no characters are provided.
		$pattern = '^\s+|\s+$';
	}

	// Use 'u' modifier for multibyte support, ensuring it handles UTF-8 properly.
	return preg_replace("/$pattern/u", "", $str);
}

/**
 * Cleans and normalizes an email address by trimming, converting to lowercase,
 * removing unnecessary whitespace and tabs, and handling specific cases like Gmail addresses.
 *
 * @param string $emailAddress The email address to clean.
 * @return string The cleaned and normalized email address.
 */
function clean_email_address(string $emailAddress): string
{
	// Trim whitespace and convert to lowercase
	$emailAddress = lower(trim($emailAddress));

	// Remove all whitespace characters including spaces, tabs, and new lines
	$emailAddress = preg_replace('/\s+/', '', $emailAddress);

	// Normalize Gmail addresses: remove dots before the '@' if the domain is gmail.com
	if (strpos($emailAddress, '@gmail.com') !== false) {
		list($localPart, $domain) = explode('@', $emailAddress);
		$localPart = str_replace('.', '', $localPart);  // Remove dots from the local part
		$emailAddress = $localPart . '@' . $domain;
	}

	return $emailAddress;
}

/**
 * Converts a UTC date string to the same date in a specified local timezone.
 *
 * @param string $utc_date The UTC date string to be converted.
 * @param string $timezone The timezone to which the UTC date should be converted. Defaults to 'America/New_York'.
 * @return string The local date-time string in the specified timezone.
 */
function convert_utc_to_local_time(string $utc_date, string $timezone = 'America/New_York'): string
{
	$dateObj = new DateTime($utc_date, new DateTimeZone('UTC'));  // Initialize the date object with UTC timezone
	$dateObj->setTimezone(new DateTimeZone($timezone));  // Change the timezone to the desired local timezone
	return $dateObj->format('Y-m-d\TH:i:sP');  // Format the date and return
}

/**
 * Returns a specified value if the given value is not empty, or null otherwise.
 * If the value to return is a closure, it will be executed with the tested value as its argument.
 *
 * @param mixed $valueToTest The value to check for emptiness.
 * @param mixed $valueToReturn The value to return if the tested value is not empty, which can be a closure.
 * 
 * @return mixed Returns null if the tested value is empty, otherwise returns the value to return or the result of the closure.
 */
function null_if_empty($valueToTest, $valueToReturn = null)
{
	if (empty($valueToTest) && $valueToTest !== '0' && $valueToTest !== 0 && $valueToTest !== 0.0) {
		return null;
	}

	// If $valueToReturn is a closure, execute it with $valueToTest as the argument.
	if ($valueToReturn instanceof \Closure) {
		return $valueToReturn($valueToTest);
	}

	// Return $valueToReturn directly if it's not a closure and $valueToTest is not empty.
	return $valueToReturn ?? $valueToTest;
}


/**
 * Trims a given string and returns null if the trimmed string is empty.
 *
 * @param string|null $value The string to trim.
 * @return string|null Returns the trimmed string or null if the resulting string is empty.
 */
function trim_or_null(?string $value): ?string
{
	if ($value === null) {
		return null;
	}

	// Assuming `trimer` is a custom function similar to `trim`, replace with `trim` if not.
	$trimmed = trimer($value);
	return null_if_empty($trimmed);
}

#region Search
function first_not_empty(...$values)
{
	foreach ($values as $value) {
		$value = trimer($value);
		if (!empty($value)) {
			return $value;
		}
	}

	return null;
}

function in($array, mixed $value)
{
	if (search($array, $value) === false) {
		return false;
	}

	return true;
}
#endregion

#region Generators
/**
 * Generate UUID version 4
 * 
 * @return string
 */
function uuidv4(): string
{
	$uuid = bin2hex(random_bytes(18));
	$uuid[8] = $uuid[13] = $uuid[18] = $uuid[23] = '-';
	$uuid[14] = '4';
	$uuid[19] = [
		'8', '9', 'a', 'b', '8', '9',
		'a', 'b', 'c' => '8', 'd' => '9',
		'e' => 'a', 'f' => 'b'
	][$uuid[19]] ?? $uuid[19];
	return $uuid;
}

#endregion