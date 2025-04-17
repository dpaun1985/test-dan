<?php
namespace App\Service\Validator;

interface ValidatorInterface
{
    /**
     * Validate the data.
     *
     * @param array $data
     * @return bool
     */
    public function validate(): bool;
}