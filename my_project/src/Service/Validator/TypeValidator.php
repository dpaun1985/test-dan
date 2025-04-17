<?php
namespace App\Service\Validator;

class TypeValidator implements ValidatorInterface
{
    public function __construct(
        private string $type
    )
    {
    }

    public function validate(): bool
    {
        $validClientTypes = ['private', 'business'];
        if (!in_array($this->type, $validClientTypes)) {
            throw new \Exception('The client type is not valid. It must be either "private" or "business".');
        }

        return true;
    }
}