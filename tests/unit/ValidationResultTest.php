<?php

declare(strict_types=1);

namespace JardisPort\Validation\Tests;

use JardisPort\Validation\ValidationResult;
use PHPUnit\Framework\TestCase;

class ValidationResultTest extends TestCase
{
    public function testIsValidReturnsTrueForEmptyErrors(): void
    {
        $result = new ValidationResult([]);

        $this->assertTrue($result->isValid());
    }

    public function testIsValidReturnsFalseForNonEmptyErrors(): void
    {
        $result = new ValidationResult(['email' => ['Invalid email']]);

        $this->assertFalse($result->isValid());
    }

    public function testGetErrorsReturnsAllErrors(): void
    {
        $errors = [
            'email' => ['Invalid email'],
            'password' => ['Too short', 'Missing special character']
        ];
        $result = new ValidationResult($errors);

        $this->assertSame($errors, $result->getErrors());
    }

    public function testHasErrorsForFieldReturnsTrueWhenFieldHasErrors(): void
    {
        $result = new ValidationResult(['email' => ['Invalid email']]);

        $this->assertTrue($result->hasErrorsForField('email'));
    }

    public function testHasErrorsForFieldReturnsFalseWhenFieldHasNoErrors(): void
    {
        $result = new ValidationResult(['email' => ['Invalid email']]);

        $this->assertFalse($result->hasErrorsForField('password'));
    }

    public function testGetErrorsForFieldReturnsErrorsForExistingField(): void
    {
        $errors = ['Invalid email', 'Email already exists'];
        $result = new ValidationResult(['email' => $errors]);

        $this->assertSame($errors, $result->getErrorsForField('email'));
    }

    public function testGetErrorsForFieldReturnsEmptyArrayForNonExistingField(): void
    {
        $result = new ValidationResult(['email' => ['Invalid email']]);

        $this->assertSame([], $result->getErrorsForField('password'));
    }

    public function testGetFieldErrorsReturnsErrorsForExistingField(): void
    {
        $errors = ['Invalid email'];
        $result = new ValidationResult(['email' => $errors]);

        $this->assertSame($errors, $result->getFieldErrors('email'));
    }

    public function testGetFieldErrorsReturnsEmptyArrayForNonExistingField(): void
    {
        $result = new ValidationResult(['email' => ['Invalid email']]);

        $this->assertSame([], $result->getFieldErrors('password'));
    }

    public function testHasFieldErrorReturnsTrueWhenFieldHasErrors(): void
    {
        $result = new ValidationResult(['email' => ['Invalid email']]);

        $this->assertTrue($result->hasFieldError('email'));
    }

    public function testHasFieldErrorReturnsFalseWhenFieldHasNoErrors(): void
    {
        $result = new ValidationResult(['email' => ['Invalid email']]);

        $this->assertFalse($result->hasFieldError('password'));
    }

    public function testHasFieldErrorReturnsFalseWhenFieldHasEmptyArray(): void
    {
        $result = new ValidationResult(['email' => []]);

        $this->assertFalse($result->hasFieldError('email'));
    }

    public function testGetAllFieldsWithErrorsReturnsFieldNames(): void
    {
        $result = new ValidationResult([
            'email' => ['Invalid email'],
            'password' => ['Too short'],
            'username' => ['Already taken']
        ]);

        $this->assertSame(['email', 'password', 'username'], $result->getAllFieldsWithErrors());
    }

    public function testGetAllFieldsWithErrorsReturnsEmptyArrayWhenNoErrors(): void
    {
        $result = new ValidationResult([]);

        $this->assertSame([], $result->getAllFieldsWithErrors());
    }

    public function testGetErrorCountReturnsCorrectCount(): void
    {
        $result = new ValidationResult([
            'email' => ['Invalid email'],
            'password' => ['Too short'],
            'username' => ['Already taken']
        ]);

        $this->assertSame(3, $result->getErrorCount());
    }

    public function testGetErrorCountReturnsZeroForNoErrors(): void
    {
        $result = new ValidationResult([]);

        $this->assertSame(0, $result->getErrorCount());
    }

    public function testGetFirstErrorReturnsFirstErrorForField(): void
    {
        $result = new ValidationResult([
            'email' => ['First error', 'Second error', 'Third error']
        ]);

        $this->assertSame('First error', $result->getFirstError('email'));
    }

    public function testGetFirstErrorReturnsNullForNonExistingField(): void
    {
        $result = new ValidationResult(['email' => ['Invalid email']]);

        $this->assertNull($result->getFirstError('password'));
    }

    public function testGetFirstErrorReturnsNullForEmptyErrorArray(): void
    {
        $result = new ValidationResult(['email' => []]);

        $this->assertNull($result->getFirstError('email'));
    }

    public function testGetFirstErrorCastsErrorToString(): void
    {
        $result = new ValidationResult(['email' => [123]]);

        $this->assertSame('123', $result->getFirstError('email'));
    }

    public function testValidationResultIsImmutable(): void
    {
        $errors = ['email' => ['Invalid email']];
        $result = new ValidationResult($errors);

        // Verify we cannot modify the errors array after creation
        $errors['password'] = ['Too short'];

        $this->assertArrayNotHasKey('password', $result->getErrors());
    }

    public function testHandlesHierarchicalErrorStructure(): void
    {
        $errors = [
            'address' => [
                'street' => ['Required'],
                'city' => ['Invalid']
            ]
        ];
        $result = new ValidationResult($errors);

        $this->assertFalse($result->isValid());
        $this->assertTrue($result->hasErrorsForField('address'));
        $this->assertSame($errors['address'], $result->getErrorsForField('address'));
    }
}
