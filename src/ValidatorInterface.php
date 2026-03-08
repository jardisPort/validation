<?php

declare(strict_types=1);

namespace JardisPort\Validation;

/**
 * Generic validator contract for validating any PHP object.
 */
interface ValidatorInterface
{
    /**
     * Validates the given object and returns a result.
     *
     * @param object $data The object to validate
     * @return ValidationResult The validation result
     */
    public function validate(object $data): ValidationResult;
}
