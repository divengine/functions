import { isUUID } from '../src/functions';

describe('isUUID', () => {
	
	test('should return true for a valid UUID', () => {
		expect(isUUID('550e8400-e29b-41d4-a716-446655440000')).toBe(true);
	});

	test('should return false for an invalid UUID', () => {
		expect(isUUID('not-a-uuid')).toBe(false);
	});

	test('should return false for a non-string value', () => {
		expect(isUUID(42)).toBe(false);
	});

});
