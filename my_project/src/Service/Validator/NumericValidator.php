<?php
namespace App\Service\Validator;

class NumericValidator implements ValidatorInterface
{
    public function __construct(
        private array $numbers
    )
    {
    }

    public function validate(): bool
    {
        foreach ($this->numbers as $number) {
            if (!is_numeric($number)) {
                throw new \Exception($number . ' is not numeric.');
            }
        }

        return true;
    }
}