<?php

/**
 * Divengine PHP Functions
 * 
 * A collection of standalone functions designed to enhance PHP capabilities,
 * providing common utilities without external dependencies. 
 * Part of the divengine* ecosystem, these functions offer atomic 
 * solutions that PHP lacks natively.
 * 
 * @package divengine
 */

namespace divengine;

/**
 * Check if a variable is a closure
 *
 * @param $t
 *
 * @return bool
 */
function is_closure(mixed $t): bool
{
	return $t instanceof \Closure;
}

/**
 * Return boolean value of a variable
 *
 * @param $value
 * @return bool
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

	return boolval($value);
}

/**
 * Return string value of a variable
 *
 * @param $value
 * @return string
 */
function string(mixed $value): string
{
	if (is_string($value)) {
		return $value;
	}

	if (is_numeric($value)) {
		return strval($value);
	}

	if (is_bool($value)) {
		return $value ? 'true' : 'false';
	}

	if (is_array($value)) {
		return json_encode($value);
	}

	if (is_object($value)) {
		return json_encode($value);
	}

	return '';
}

/**
 * Return string value of a variable or null
 *
 * @param $value
 * @return string
 */
function stringOrNull(mixed $value): ?string
{
	if (empty($value)) {
		return null;
	}

	return string($value);
}

/**
 * Return upper case string value of a variable
 */
function upper(?string $value): string
{
	if ($value === null) {
		return '';
	}

	return mb_strtoupper(string($value));
}

function upperOrNull(?string $value): ?string
{
	if ($value === null) {
		return null;
	}

	return strtoupper($value);
}

/**
 * Return lower case string value of a variable
 */
function lower(?string $value): string
{
	if ($value === null) {
		return '';
	}

	return mb_strtolower($value);
}

function arrayOfStringsOrNull(?array $value): ?array
{
	if ($value === null) {
		return null;
	}

	if (empty($value)) {
		return null;
	}

	$value = array_map(fn ($item) => string($item), $value);

	return $value;
}

function arrayOrNull(?array $value): ?array
{
	if ($value === null) {
		return null;
	}

	if (empty($value)) {
		return null;
	}

	return $value;
}

function intOrMin($value, int $min): int
{
	$value = intval($value);

	if ($value < $min) {
		return $min;
	}

	return $value;
}

function intOrNull($value): ?int
{
	if (empty($value)) {
		return null;
	}

	return intval($value);
}

function nonZeroOrNull(int $value)
{
	if ($value === 0) {
		return null;
	}

	return $value;
}

/**
 * Convert a non empty string to a integer, return null if empty
 *
 * @param $value
 */
function nullableInt($value): ?int
{
	if (!is_numeric($value)) {
		return null;
	}

	return intval($value);
}

function nullableFloat($value): ?float
{
	if (empty($value)) {
		return null;
	}

	return floatval($value);
}

/**
 * Replace a string with another string
 */
function replace(?string $search, ?string $replace, ?string $string): string
{
	$string = $string ?? '';
	$search = $search ?? '';
	$replace = $replace ?? '';

	return str_replace($search, $replace, $string);
}

/**
 * Check if a string contains another string
 */
function contains(?string $string, ?string $search): bool
{
	$string = $string ?? '';
	$search = $search ?? '';

	return strpos($string, $search) !== false;
}

/**
 * Check if a string is a valid uuid, and then return it or throw exception server error
 */
function uuidOrDie(?string $uuid, $exception = null): string
{
	if (!is_uuid($uuid)) {
		throw $exception ?? new \Exception("$uuid is not valid UUID", 500);
	}

	return $uuid;
}

function uuidOrNullOrDie(?string $uuid): ?string
{
	if (empty($uuid)) {
		return null;
	}

	return uuidOrDie($uuid);
}

/**
 * Check if a string is a valid uuid
 */
function is_uuid(string $uuid): bool
{
	return preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/', "$uuid") === 1;
}
/*
 * Check if a string is a valid array of uuids
 */
function is_array_of_uuid($uuids): bool
{

	if (!is_array($uuids)) {
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
 * Check if a string is a valid datetime, and then return it or throw exception server error
 */
function dateTimeOrDie($date): string
{
	if (is_ISO8061($date)) {
		return $date;
	}

	throw new \Exception("$date is not valid datetime. The value must be ISO 8061 YYYY-MM-DDThh:ii:ss-0000.", 500);
}

function dateTimeOrNullOrDie($date): ?string
{
	if (empty($date)) {
		return null;
	}

	return dateTimeOrDie($date);
}


function is_valid_date($date)
{
	$date_arr = explode('-', $date);

	if (count($date_arr) != 3) {
		return false;
	}

	$year = intval($date_arr[0]);
	$month = intval($date_arr[1]);
	$day = intval($date_arr[2]);

	if (!checkdate($month, $day, $year)) {
		return false;
	}

	return true;
}

/**
 * Check if a string is a valid date, and then return it or throw exception server error
 */
function dateOrDie($date): string
{
	if (is_valid_date($date)) {
		return $date;
	}

	throw new \Exception("$date is not valid date", 500);
}

/**
 * Check if a string is a valid date
 */
function is_ISO8061($date): bool
{
	return preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.\d+)?[+-]\d{2}:\d{2}$/', "$date") === 1;
}

/**
 * Convert a datetime string to a date string
 * 
 * @param string $datetime
 * 
 * @return string
 */
function datetimeToDate(string $datetime)
{
	$datetime = new DateTime($datetime);
	return $datetime->format('Y-m-d');
}

/**
 * Cast as boolean and return negative
 * 
 * @param $v
 * 
 * @return bool
 */
function not($v): bool
{
	return !boolean($v);
}

/**
 * Alias of boolean
 * 
 * @param $value
 * 
 * @return bool
 */
function is($value): bool
{
	return boolean($value);
}

/**
 * Check if a string is a valid email
 */
function is_email(string $email): bool
{
	return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Check if a string is a valid url
 */
function is_url(string $url): bool
{
	return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Check if a string is a valid email or null, and then return it or throw exception server error
 */
function emailOrNullOrDie(?string $email): ?string
{
	if ($email === null) {
		return null;
	}

	if (is_email($email)) {
		return $email;
	}

	throw new \Exception("$email is not valid email", 500);
}

/**
 * Check if a string is a valid url or null, and then return it or throw exception server error
 */
function urlOrNullOrDie(?string $url): ?string
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

	throw new \Exception("$original is not valid url", 500);
}

function urlOrNull(?string $url): ?string
{
	if ($url === null) {
		return null;
	}

	if (is_url($url)) {
		return $url;
	}

	return null;
}

/**
 * Check if a string is a valid email, and then return it or throw exception server error
 */
function emailOrDie(?string $email): string
{
	if ($email === null) {
		throw new \Exception("Email is null", 500);
	}

	if (is_email($email)) {
		return $email;
	}

	throw new \Exception("$email is not valid email", 500);
}

/**
 * Check if a string is a valid url, and then return it or throw exception server error
 */
function urlOrDie(?string $url): string
{
	if ($url === null) {
		throw new \Exception("Url is null", 500);
	}

	if (is_url($url)) {
		return $url;
	}

	throw new \Exception("$url is not valid url", 500);
}

/**
 * Check if a string is a valid hex color of the form #FFFFFF
 */
function hexColorOrDie(?string $color): string
{
	if (empty($color)) {
		throw new \Exception("Color is null", 500);
	}

	$color = strtoupper($color);
	$matches = preg_match('/^#[0-9a-zA-Z]{6}$/', $color);

	if ($matches === 1) {
		return $color;
	}

	throw new \Exception("$color is not a valid color", 500);
}

/**
 * Map an object or array of objects to another object or array of objects
 *
 * @param $object
 * @param $map
 * @param array<string> $onlyFields
 *
 * @return mixed
 */
function map(mixed $object, mixed $map): mixed
{
	if ($object === null) {
		$object = new \stdClass();
	}

	if (is_array($object)) {
		$newObjects = [];
		foreach ($object as $obj) {
			$newObjects[] = map((object) $obj, $map);
		}
		return $newObjects;
	}

	if (is_closure($map)) {
		return $map($object);
	}

	$newObject = new \stdClass();

	if (is_object($map)) {
		$newObject = clone $map;
		$map = array_keys((array) $map);
	}

	foreach ($map as $key => $value) {
		if (is_int($key)) {
			$key = $value;
			if (strpos($value, ':') !== false)
				throw new Exception("Wrong map format. You can't use ':' in map key");
		}

		if (is_closure($value)) {
			$passValue = null;

			if (isset($object->$key)) {
				$passValue = $object?->$key;
			}

			$newObject->$key = $value($object, $passValue);
		} else if (is_string($value)) {

			$modifiers = [];
			if (strpos($value, ':') !== false) {
				$modifiers = explode(':', $value);
				$value = trim($modifiers[0]);
				if (empty($value)) $value = $key;
				$modifiers = trim($modifiers[1]);
				$modifiers = explode(',', $modifiers);
			}

			if (property_exists($object, $value)) {
				$newObject->$key = $object->$value;
			} elseif (method_exists($object, $value)) {
				$newObject->$key = $object->$value();
			} else {
				$newObject->$key = null;
			}

			if (!empty($modifiers)) {
				foreach ($modifiers as $modifier) {
					if (is_callable($modifier)) {
						try {
							$newObject->$key = $modifier($newObject->$key);
						} catch (\Exception $ex) {
							$context = "Applying modifier $modifier to $key with value '" . string($newObject->$key) . "'";
							throw new \Exception($ex->getMessage() . ". Context: $context", 500, $ex);
						}
					}
				}
			}
		} else {
			$newObject->$key = $value;
		}
	}
	return $newObject;
}
function notificationLink($link)
{
	$link = trim(lower($link));
	$link = '/' . trim($link, '/') . '/';
	if (preg_match('/^\/[a-z0-9\/-]+\/$/', $link)) {
		return $link;
	} else {
		return null;
	}
}

function arrayOfInts($array)
{
	if (!is_array($array)) {
		return [];
	}

	$newArray = [];
	foreach ($array as $item) {
		$newArray[] = intval($item);
	}

	return $newArray;
}

function arrayOfStrings($array)
{
	if (!is_array($array)) {
		return [];
	}

	$newArray = [];
	foreach ($array as $item) {
		$newArray[] = string($item);
	}

	return $newArray;
}

function commaSeparatedInts($string)
{
	$array = explode(',', $string);
	return arrayOfInts($array);
}

function stringOrDie($string): string
{
	$string = string($string);
	if (empty($string)) {
		throw new \Exception("String is not valid", 500);
	}

	return $string;
}

function dieIfEmpty($value)
{
	if (empty($value)) {
		throw new \Exception("Empty value", 500);
	}

	return $value;
}

function division($a, $b)
{
	if ($b == 0) {
		return 0;
	}

	return $a / $b;
}

function booleanOrDie($value)
{
	if ($value === null) {
		throw new \Exception("Value is null", 500);
	}

	if (is_bool($value)) {
		return $value;
	}

	if (is_string($value)) {
		$value = lower($value);
		if ($value === 'true') {
			return true;
		} else if ($value === 'false') {
			return false;
		}
	}

	throw new \Exception("Value is not boolean", 500);
}

function objectOrNull($object)
{
	if (is_object($object)) {
		return $object;
	}

	return null;
}

function objectOrNullOrDie($object)
{
	if (is_object($object)) {
		return $object;
	}

	if ($object === null) {
		return null;
	}

	throw new \Exception("Value is not object", 500);
}

function phoneNumber($value)
{
	if ($value === null) return null;
	$value = str_replace([' ', '-', '+', '(', ')'], '', $value);
	$value = mb_substr($value, 0, 10);
	return intval($value);
}

function userNameOrDie($value)
{
	$value = lower(substr(preg_replace('/[^a-zA-Z0-9]+/', '', $value), 0, 15));

	if (empty($value) || strlen($value) < 5 || is_numeric($value)) {
		throw new Exception("El nombre de usuario es inválido");
	}

	return $value;
}

function upperOrDie($value)
{
	if (empty($value) || strtoupper($value) !== $value) {
		throw new Exception("El valor debe ser uppercase");
	}

	return $value;
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
	$text = strip_tags("$text"); // Elimina HTML.
	$text = preg_replace('/\s+/', ' ', $text); // Reemplaza múltiples espacios por uno solo.
	$text = trim($text); // Elimina espacios al principio y al final.

	if (mb_strlen($text) <= $limit) {
		// Si el texto es más corto o igual al límite, simplemente lo retorna.
		return $text;
	}

	// Corte inicial al límite para evitar procesamiento innecesario.
	$cutText = mb_substr($text, 0, $limit);
	$lastSpace = mb_strrpos($cutText, ' ');

	if ($lastSpace !== false) {
		// Corta el texto hasta el último espacio para evitar cortar palabras a la mitad.
		$cutText = mb_substr($cutText, 0, $lastSpace);
	}

	// Verificar que no se corte en medio de una URL, hashtag o mención.
	$pattern = '/(http[s]?:\/\/[^\s]+|@[^\s]+|#[^\s]+)/u';
	preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);
	foreach ($matches[0] as $match) {
		$startPos = $match[1];
		$endPos = $startPos + mb_strlen($match[0]);

		if ($startPos < $limit && $endPos > $limit) {
			// Si el corte está en medio de una URL, hashtag o mención, ajustar el corte.
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
 * Check if a value is in a string, numeric, array or object
 * 
 * @param mixed $needle
 * @param mixed $haystack
 * 
 * @return bool
 */
function pad(mixed $value, int $length, mixed $padValue = ' ', int $padType = STR_PAD_RIGHT)
{
	if (empty($padValue) && $padValue !== 0 && $padValue !== '0') {
		return $value;
	}

	// objects are not supported
	if (is_object($value)) {
		throw new \Exception("Objects are not supported for pad values", 500);
	}

	// boolean values are not supported
	if (is_bool($value)) {
		throw new \Exception("Boolean values are not supported for pad values", 500);
	}

	// convert allways to array
	$arr = divide($value);

	$len = count($arr);

	if ($len >= $length) {
		return $value;
	}

	$pad = array_fill(0, $length - $len, $padValue);

	if ($padType === STR_PAD_LEFT) {
		$arr = array_merge($pad, $arr);
	} else {
		$arr = array_merge($arr, $pad);
	}

	return match (true) {
		is_string($value) => implode('', $arr),
		is_int($value) => intval(implode('', $arr)),
		is_float($value) => floatval(implode('', $arr)),
		default => $arr
	};
}

/*
 * Check if object has required valid fields
 * 
 * @param object $object
 * @param array $requiredFields
 * 
 * @return bool
 */
function validateRequiredFieldsOfList($array, array $requiredFields): bool
{

	if (!is_array($array)) {
		return false;
	}

	foreach ($array as $item) {
		if (!validateRequiredFieldsOfItem($item, $requiredFields)) {
			return false;
		}
	}

	return true;
}

/*
 * Check if object has required valid fields
 * 
 * @param object $object
 * @param array $requiredFields
 * 
 * @return bool
 */
function validateRequiredFieldsOfItem($object, array $requiredFields): bool
{

	if (!is_object($object)) {
		return false;
	}

	foreach ($requiredFields as $field => $validator) {
		if (is_numeric($field) && is_string($validator)) {
			$field = $validator;
			$validator = fn ($v) => true;
		}

		if (!property_exists($object, $field) || !$validator($object->$field)) {
			return false;
		}
	}

	return true;
}

/**
 * Generate a new random hash. Mostly used for temporals
 *
 * @return string
 */
function randomHash()
{
	return md5(uniqid("", true));
}

/**
 * Multibyte trim
 *
 * @param $str
 * @param ?string $chars
 * @return string|string[]|null
 */
function trimer($str, $chars = null)
{
	if ($chars !== null) {
		$chars = preg_quote($chars);
		$pattern = "^[{$chars}]+|[{$chars}]+\$";
	} else {
		$pattern = '^[\\s]+|[\\s]+$';
	}

	return preg_replace("/$pattern/", "", $str . "");
}

/**
 * Valid date
 *
 * @param string $date
 * @param string $format
 *
 * @return bool
 */
function isValidDate(string $date, string $format = 'Y-m-d'): bool
{
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) === $date;
}

function clearEmailAddress($emailAddress)
{
	$emailAddress = trim($emailAddress);
	$emailAddress = lower($emailAddress);
	$emailAddress = str_replace(" ", "", $emailAddress);
	$emailAddress = str_replace("\t", "", $emailAddress);
	$emailAddress = str_replace("\n", "", $emailAddress);
	$emailAddress = str_replace("\r", "", $emailAddress);

	// remove dot from gmail address before @
	$pos = strpos($emailAddress, "@gmail.com");
	if ($pos !== false) {
		$emailAddress = str_replace(".", "", substr($emailAddress, 0, $pos)) . substr($emailAddress, $pos);
	}

	return $emailAddress;
}

/**
 * Remove all properties in an object except the ones passes in the array
 *
 * @param array $properties , array of properties to keep
 * @param object $object, object to clean
 * @return object, clean object
 */
function filterObjectProperties($properties, $object)
{
	$objNew = new stdClass();
	foreach ($properties as $prop) {
		$objNew->$prop = $object->$prop ?? null;
	}
	return $objNew;
}

function checkUSDT($value): bool
{
	return preg_match('/^T[A-Za-z1-9]{33}$/', $value);
}

/**
 * Coverts a UTC date string to the same date in local timezone
 *
 * @param string $utc_date
 * @return string
 */
function dateUTCToLocalTimezone(string $utc_date): string
{
	$dateObj = new DateTime($utc_date, new DateTimeZone('UTC'));
	$dateObj->setTimezone(new DateTimeZone('America/New_York'));
	return str_replace(' ', 'T', $dateObj->format('Y-m-d H:i:sP'));
}

function nullIfEmpty($valueToTest, $valueToReturn = null)
{
	if (empty($valueToTest)) {
		return null;
	}

	if (empty($valueToReturn)) {
		return $valueToTest;
	}

	if (is_closure($valueToReturn)) {
		return $valueToReturn($valueToTest);
	}

	return $valueToReturn;
}

function trimOrNull($value)
{
	return nullIfEmpty(trimer($value));
}

function firstNotEmpty(...$values)
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

/**
 * Generate UUID version 4
 * 
 * @return string
 */
function generateUUIDv4(): string
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
