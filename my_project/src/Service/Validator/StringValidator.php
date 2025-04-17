<?php
namespace App\Service\Validator;

class StringValidator implements ValidatorInterface
{
    public function __construct(
        private array $strings
    )
    {
    }

    public function validate(): bool
    {
        foreach ($this->strings as $string) {
            if (!is_string($string)) {
                throw new \Exception($string . ' is not a string.');
            }
        }

        return true;
    }
}