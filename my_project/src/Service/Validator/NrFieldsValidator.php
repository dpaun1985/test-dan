<?php
namespace App\Service\Validator;

class NrFieldsValidator implements ValidatorInterface
{
    public function __construct(
        private array $client
    )
    {
    }

    public function validate(): bool
    {
        if (count($this->client) !== 6) {
            throw new \Exception('The client array must contain exactly 6 elements.');
        }

        return true;
    }
}