<?php
namespace App\Service;

use App\Service\Validator\ValidatorInterface;

class ClientValidator
{
    private array $validators = [];
    /**
     * Validate the client data.
     *
     * @param array $clientData
     * @return bool
     */
    public function validate(): bool
    {
        foreach ($this->validators as $validator) {
            $validator->validate();
        }

        return true;
    }

    public function add(ValidatorInterface $validator): self
    {
        $this->validators[] = $validator;

        return $this;
    }
}