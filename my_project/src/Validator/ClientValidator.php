<?php
namespace App\Validator;

class ClientValidator {
    public function validate(array $client): bool
    {
        $this->validateClientLength($client);
        $this->validateString($client);
        $this->validateNumeric($client);
        $this->validateDateFormat($client[0]);
        $this->validateClientType($client[2]);
        $this->validateActionType($client[3]);

        return true;
    }

    private function validateClientLength(array $client): bool
    {
        if (count($client) !== 6) {
            throw new \Exception('The client array must contain exactly 6 elements.');
        }

        return true;
    }

    private function validateString(array $client): bool
    {
        if (!is_string($client[2])) {
            throw new \Exception('The client array must contain string on the 3rd position.');
        }
        if (!is_string($client[3])) {
            throw new \Exception('The client array must contain string on the 4th position.');
        }
        if (!is_string($client[5])) {
            throw new \Exception('The client array must contain string on the 6th position.');
        }

        return true;
    }

    private function validateNumeric(array $client): bool
    {
        if (!is_numeric($client[1])) {
            throw new \Exception('The client array must contain numeric on the 2nd position.');
        }
        if (!is_numeric($client[4])) {
            throw new \Exception('The client array must contain numeric on the 5th position.');
        }

        return true;
    }

    private function validateDateFormat(string $date): bool
    {
        $data = \DateTime::createFromFormat('Y-m-d', $date);
        if (!$data || $data->format('Y-m-d') !== $date) {
            throw new \Exception('The date format is not valid.');

        }

        return true;
    }

    private function validateClientType(string $clientType): bool
    {
        $validClientTypes = ['private', 'business'];
        if (!in_array($clientType, $validClientTypes)) {
            throw new \Exception('The client type is not valid. It must be either "private" or "business".');
        }

        return true;
    }

    private function validateActionType(string $clientType): bool
    {
        $validActionTypes = ['deposit', 'withdraw'];
        if (!in_array($clientType, $validActionTypes)) {
            throw new \Exception('The action type is not valid. It must be either "deposit" or "withdraw".');
        }

        return true;
    }
}