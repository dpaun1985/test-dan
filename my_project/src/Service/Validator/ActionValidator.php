<?php
namespace App\Service\Validator;

class ActionValidator implements ValidatorInterface
{
    public function __construct(
        private string $action
    )
    {
    }

    public function validate(): bool
    {
        $validActionTypes = ['deposit', 'withdraw'];
        if (!in_array($this->action, $validActionTypes)) {
            throw new \Exception('The action type is not valid. It must be either "deposit" or "withdraw".');
        }

        return true;
    }
}