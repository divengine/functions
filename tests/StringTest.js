import { string } from '../src/functions';

describe('string', () => {
	
	test('should return the string equivalent of the input value', () => {
		expect(string('test')).toBe('test');
		expect(string(42)).toBe('42');
		expect(string(BigInt(42))).toBe('42');
		expect(string(true)).toBe('true');
		expect(string(false)).toBe('false');
		expect(string({})).toBe('{}');
		expect(string([])).toBe('[]');
		expect(string(null)).toBe('');
	});

	test('should return an empty string when the criteria is not met', () => {
		expect(string('test', false)).toBe('');
		expect(string('test', () => false)).toBe('');
	});

	test('should return the string equivalent of the input value when the criteria is met', () => {
		expect(string('test', true)).toBe('test');
		expect(string('test', () => true)).toBe('test');
	});

});