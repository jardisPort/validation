# Jardis Validation Port

![Build Status](https://github.com/jardisPort/validation/actions/workflows/ci.yml/badge.svg)
[![License: PolyForm Shield](https://img.shields.io/badge/License-PolyForm%20Shield-blue.svg)](LICENSE.md)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.2-777BB4.svg)](https://www.php.net/)
[![PHPStan Level](https://img.shields.io/badge/PHPStan-Level%208-brightgreen.svg)](phpstan.neon)
[![PSR-12](https://img.shields.io/badge/Code%20Style-PSR--12-blue.svg)](phpcs.xml)

> Part of the **[Jardis Business Platform](https://jardis.io)** — Enterprise-grade PHP components for Domain-Driven Design

Validation contracts for object and field-level input verification. Three interfaces covering full object validation, individual value validation, and structured validation results with field-level error access. Swap validation libraries without changing a single line of domain code.

---

## Interfaces

### **ValidatorInterface**

`JardisPort\Validation\ValidatorInterface`

Generic contract for validating any PHP object. The validator receives the full object and returns a structured result.

| Method | Signature | Description |
|--------|-----------|-------------|
| `validate` | `validate(object $data): ValidationResult` | Validates the given object and returns an immutable result. |

### **ValueValidatorInterface**

`JardisPort\Validation\ValueValidatorInterface`

Stateless contract for validating individual values. Reusable across different fields with runtime options.

| Method | Signature | Description |
|--------|-----------|-------------|
| `validateValue` | `validateValue(mixed $value, array $options = []): ?string` | Validates a single value. Returns an error message string on failure, `null` on success. |

### **ValidationResult**

`JardisPort\Validation\ValidationResult`

Immutable value object holding a hierarchical error structure. Returned by `ValidatorInterface::validate()`.

| Method | Signature | Description |
|--------|-----------|-------------|
| `isValid` | `isValid(): bool` | Returns `true` when no errors were recorded. |
| `getErrors` | `getErrors(): array` | Returns the full hierarchical error array. |
| `hasErrorsForField` | `hasErrorsForField(string $field): bool` | Check whether a specific field has any errors. |
| `getErrorsForField` | `getErrorsForField(string $field): array` | Get all errors for a specific field. |
| `getFieldErrors` | `getFieldErrors(string $field): array` | Get all errors for a specific field (alias for `getErrorsForField`). |
| `hasFieldError` | `hasFieldError(string $field): bool` | Returns `true` when a specific field has at least one error. |
| `getFirstError` | `getFirstError(string $field): ?string` | Get the first error message for a field, or `null`. |
| `getAllFieldsWithErrors` | `getAllFieldsWithErrors(): array` | Returns an array of all field names that have errors. |
| `getErrorCount` | `getErrorCount(): int` | Returns the number of fields with errors. |

---

## Installation

```bash
composer require jardisport/validation
```

## Usage

```php
use JardisPort\Validation\ValidatorInterface;
use JardisPort\Validation\ValueValidatorInterface;
use JardisPort\Validation\ValidationResult;

// Type-hint against ValidatorInterface for object-level validation
class RegistrationHandler
{
    public function __construct(
        private readonly ValidatorInterface $validator,
    ) {}

    public function handle(object $command): void
    {
        $result = $this->validator->validate($command);

        if (!$result->isValid()) {
            // Access field-level errors
            $emailErrors = $result->getErrorsForField('email');
            $firstError = $result->getFirstError('password');
            throw new \InvalidArgumentException('Validation failed');
        }

        // Proceed with the command
    }
}

// Type-hint against ValueValidatorInterface for reusable single-value checks
class EmailField
{
    public function __construct(
        private readonly ValueValidatorInterface $emailValidator,
    ) {}

    public function validate(string $email): ?string
    {
        return $this->emailValidator->validateValue($email);
    }
}
```

## Implemented by

- **[jardissupport/validation](https://github.com/jardisSupport/validation)** — Attribute-driven object validation and composable value validators with support for nested structures

## Documentation

Full documentation, guides, and API reference:

**[jardis.io/docs/port/validation](https://jardis.io/docs/port/validation)**

## License

This package is licensed under the [PolyForm Shield License 1.0.0](LICENSE.md). Free for all use except building competing frameworks or developer tooling.

---

**[Jardis](https://jardis.io)** · [Documentation](https://jardis.io/docs) · [Headgent](https://headgent.com)
