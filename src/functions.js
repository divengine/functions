/**
 * Div JS Functions
 * 
 * A collection of standalone functions designed to enhance JS capabilities,
 * providing common utilities without external dependencies. 
 * Part of the divengine* ecosystem, these functions offer atomic 
 * solutions that JS lacks natively.
 * 
 * @package divengine/functions
 * @author  Rafa Rodriguez @rafageist [https://rafageist.com]
 * @version 1.0.0
 *
 * @link    https://divengine.org
 * @link    https://github.com/divengine/functions
 */

/**
 * Check if a string is a valid URL
 */
export function isUrl(url) {
    try {
        new URL(url);
        return true;
    } catch (e) {
        return false;
    }
}

/**
 * Checks if the provided variable is a function (equivalent to Closure in PHP).
 *
 * @param {*} variable The variable to check.
 * @return {boolean} Returns true if the variable is a function, false otherwise.
 */
export function isClosure(variable) {
    return typeof variable === 'function';
}

/**
 * Checks if a string is a valid UUID (Universally Unique Identifier).
 * UUIDs are in the format of 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx' where 'x' is a hexadecimal digit.
 *
 * @param {string} uuid The string to validate.
 * @return {boolean} Returns true if the string is a valid UUID, otherwise false.
 */
export function isUUID(uuid) {
    return /^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i.test(uuid);
}

/**
 * Checks if a string is a valid ISO 8601 date.
 *
 * @param {string} date The date string to validate.
 * @return {boolean} Returns true if the string is a valid ISO 8601 date, false otherwise.
 */
export function isISO8601(date) {
    const pattern = /^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(\.\d+)?(Z|([+-])(\d{2}):(\d{2}))$/;
    return pattern.test(date);
}

/**
 * Validates whether the given string is a correctly formatted date according to the specified format.
 * This function checks not only if the date matches the format but also if it is a valid calendar date.
 *
 * @param {string} date The date string to validate.
 * @param {string} format The format to validate against, defaults to 'YYYY-MM-DD'.
 * @return {boolean} Returns true if the string is a valid date according to the format and a real calendar date, otherwise false.
 */
export function isValidDate(date, format = 'YYYY-MM-DD') {
    const regex = {
        'Y-m-d': /^(\d{4})-(\d{2})-(\d{2})$/,
        'd-m-Y': /^(\d{2})-(\d{2})-(\d{4})$/,
        // You can add more formats as needed
    }[format];

    if (!regex) {
        throw new Error(`Format ${format} is not supported.`);
    }

    const match = date.match(regex);
    if (!match) {
        return false;
    }

    const [_, year, month, day] = match.map(Number);

    const isValidYear = year > 0;
    const isValidMonth = month >= 1 && month <= 12;
    const isValidDay = day >= 1 && day <= new Date(year, month, 0).getDate();

    return isValidYear && isValidMonth && isValidDay;
}

/**
 * Checks if all elements in an array are valid UUIDs.
 *
 * @param {string[]} uuids The array to validate.
 * @return {boolean} Returns true if every element in the array is a valid UUID, otherwise false.
 */
export function isArrayOfUUID(uuids) {
    if (!Array.isArray(uuids) || uuids.length === 0) {
        return false;
    }
    return uuids.every(uuid => isUUID(uuid));
}

/**
 * Checks if a string is a valid email address according to the format defined in the FILTER_VALIDATE_EMAIL filter.
 *
 * @param {string} email The email address to validate.
 * @return {boolean} Returns true if the string is a valid email address, otherwise false.
 */
export function isEmail(email) {
    const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return pattern.test(email);
}

/**
 * Checks if a given string is a valid hexadecimal color in the form #FFFFFF.
 *
 * @param {string} color The color string to validate.
 * @return {boolean} Returns true if the string is a valid hex color, otherwise false.
 */
export function isHexColor(color) {
    return /^#([0-9A-F]{6}|[0-9A-F]{3})$/i.test(color);
}

/**
 * Checks if the given string is a valid USDT (Tether) wallet address.
 * For TRON-based addresses, they typically start with 'T' followed by 33 alphanumeric characters.
 *
 * @param {string} value The string to validate as a USDT address.
 * @return {boolean} Returns true if the string is a valid USDT wallet address, otherwise false.
 */
export function isUSDT(value) {
    return /^T[A-Za-z0-9]{33}$/.test(value);
}

/**
 * Checks if a value can be interpreted as a boolean.
 *
 * @param {*} value The value to evaluate.
 * @return {boolean} Returns true if the value can be interpreted as a boolean, otherwise false.
 */
export function isBoolean(value) {
    if (typeof value === 'boolean') {
        return true;
    }
    if (typeof value === 'string') {
        const normalizedValue = value.toLowerCase();
        return ['true', 'false'].includes(normalizedValue);
    }
    return false;
}

/**
 * Checks if a given string is entirely in uppercase.
 *
 * @param {string} value The string to check.
 * @return {boolean} Returns true if the string is entirely uppercase, otherwise returns false.
 */
export function isUpper(value) {
    return value === value.toUpperCase();
}

/**
 * Checks if a given string is entirely in lowercase.
 *
 * @param {string} value The string to check.
 * @return {boolean} Returns true if the string is entirely lowercase, otherwise returns false.
 */
export function isLower(value) {
    return value === value.toLowerCase();
}

/**
 * Returns the input if it is not null or empty, otherwise returns null.
 *
 * @param {*} value The value to check.
 * @return {*} Returns the input if not null or empty, otherwise null.
 */
export function somethingOrNull(value) {
    return (value === null || value === undefined || value === '') ? null : value;
}

/**
 * Converts a given value to its string equivalent.
 *
 * @param {*} value The value to be converted to a string.
 * @param {Function|boolean} [criteria=null] A callable function or boolean that determines if the value should be converted.
 * 
 * @return {string} Returns the string equivalent of the input value.
 */
export function string(value, criteria = null) {
    if (criteria !== null) {
        if (typeof criteria === 'function') {
            if (!criteria(value)) {
                return '';
            }
        } else if (criteria === false) {
            return '';
        }
    }

    if (typeof value === 'string') {
        return value;
    }

    if (typeof value === 'number' || typeof value === 'bigint') {
        return String(value);
    }

    if (typeof value === 'boolean') {
        return value ? 'true' : 'false';
    }

	if (Array.isArray(value)) {
        const result = JSON.stringify(value);
        if (result !== undefined) {
            return result;
        }
    }

    if (value !== null && typeof value === 'object')
	{
		if (typeof value.toString === 'function' && value.toString !== Object.prototype.toString) {
			let v = value.toString();
			if (v !== '[object Object]') {
				return '{}';
			}
		}
		else 
		{
			const result = JSON.stringify(value);
			if (result !== undefined) {
				return result;
			}
		}
    }
	
    return '';
}

/**
 * Simple wrapper for error handling in async/sync operations
 */ 
export const attempt = {
    async async(operation) {
        try {
            return await operation();
        } catch (error) {
            return error;
        }
    },
    sync: function (operation) {
        try {
            return operation();
        } catch (error) {
            return error;
        }
    }
};

