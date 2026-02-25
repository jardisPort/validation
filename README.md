# JardisPort Validation

![Build Status](https://github.com/jardisPort/validation/actions/workflows/ci.yml/badge.svg)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-blue.svg)](https://www.php.net/)
[![PHPStan Level](https://img.shields.io/badge/PHPStan-Level%208-success.svg)](phpstan.neon)
[![PSR-4](https://img.shields.io/badge/autoload-PSR--4-blue.svg)](https://www.php-fig.org/psr/psr-4/)
[![PSR-12](https://img.shields.io/badge/code%20style-PSR--12-orange.svg)](phpcs.xml)

This package provides validation interfaces for a domain-driven design (DDD) approach.

## Installation

Install the package via Composer:

```bash
composer require jardisport/validation
```

## Requirements

- PHP >= 8.2

## Usage

The package provides validation interfaces and an immutable `ValidationResult` value object for standardized validation across your application.

### ValidatorInterface

Generic validator contract for validating any PHP object:

```php
use JardisPort\Validation\ValidatorInterface;
use JardisPort\Validation\ValidationResult;

class UserValidator implements ValidatorInterface
{
    public function validate(object $data): ValidationResult
    {
        $errors = [];

        if (empty($data->email)) {
            $errors['email'][] = 'Email is required';
        }

        if (empty($data->password)) {
            $errors['password'][] = 'Password is required';
        }

        return new ValidationResult($errors);
    }
}
```

### ValueValidatorInterface

Contract for value-level validators that are stateless and reusable:

```php
use JardisPort\Validation\ValueValidatorInterface;

class EmailValidator implements ValueValidatorInterface
{
    public function validateValue(mixed $value, array $options = []): ?string
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email format';
        }

        return null;
    }
}
```

### ValidationResult

Immutable value object for handling validation results:

```php
use JardisPort\Validation\ValidationResult;

$result = new ValidationResult([
    'email' => ['Invalid email format'],
    'password' => ['Too short', 'Missing special character']
]);

// Check validation status
if (!$result->isValid()) {
    // Get all errors
    $allErrors = $result->getErrors();

    // Get errors for specific field
    $emailErrors = $result->getErrorsForField('email');

    // Get first error for a field
    $firstError = $result->getFirstError('password'); // "Too short"

    // Check if field has errors
    if ($result->hasFieldError('email')) {
        // Handle email errors
    }

    // Get all fields with errors
    $fieldsWithErrors = $result->getAllFieldsWithErrors(); // ['email', 'password']

    // Get error count
    $count = $result->getErrorCount(); // 2
}
```

### ValidationResult Methods

- `isValid(): bool` - Returns true if no errors exist
- `getErrors(): array` - Returns all errors in hierarchical structure
- `hasErrorsForField(string $field): bool` - Check if field has errors
- `getErrorsForField(string $field): array` - Get errors for specific field
- `getFieldErrors(string $field): array` - Alias for getErrorsForField
- `hasFieldError(string $field): bool` - Check if field has non-empty errors
- `getAllFieldsWithErrors(): array` - Get array of field names with errors
- `getErrorCount(): int` - Get total count of fields with errors
- `getFirstError(string $field): ?string` - Get first error message for field

## Development

### Running Tests

```bash
# Run tests
make phpunit

# Run tests with coverage
make phpunit-coverage
```

### Code Quality

The project uses PHPStan for static analysis and PHP_CodeSniffer for code style checks:

```bash
# Run PHPStan
vendor/bin/phpstan analyse

# Run PHP_CodeSniffer
vendor/bin/phpcs
```

### Pre-commit Hook

A pre-commit hook is automatically installed via Composer's post-install script to ensure code quality before commits.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

- Issues: [GitHub Issues](https://github.com/JardisPort/validation/issues)
- Email: jardisCore@headgent.dev

## Authors

- Jardis Core Development (jardisCore@headgent.dev)

## Keywords

- validation
- interfaces
- JardisPort
- Headgent
- DDD (Domain-Driven Design)
