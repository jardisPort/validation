<?php

declare(strict_types=1);

namespace JardisPort\Validation;

/**
 * Contract for value-level validators.
 * Value validators validate any value without coupling to field names.
 * They are stateless and reusable with runtime options.
 */
interface ValueValidatorInterface
{
    /**
     * Validates a value with optional runtime configuration.
     *
     * @param mixed $value The value to validate
     * @param array<string, mixed> $options Runtime validation options
     * @return string|null Error message if invalid, null if valid
     */
    public function validateValue(mixed $value, array $options = []): ?string;
}
