import { attempt } from '../src/functions';

describe('attempt', () => {

    describe('sync', () => {
        test('should return the result of a successful sync operation', () => {
            const result = attempt.sync(() => 42);
            expect(result).toBe(42);
        });

        test('should return the error of a failed sync operation', () => {
            const error = new Error('Sync Error');
            const result = attempt.sync(() => {
                throw error;
            });
            expect(result).toBe(error);
        });
    });

    describe('async', () => {
        test('should return the result of a successful async operation', async () => {
            const result = await attempt.async(async () => 42);
            expect(result).toBe(42);
        });

        test('should return the error of a failed async operation', async () => {
            const error = new Error('Async Error');
            const result = await attempt.async(async () => {
                throw error;
            });
            expect(result).toBe(error);
        });
    });

});
