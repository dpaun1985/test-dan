<?php
namespace App\Service\Validator;

class DateValidator implements ValidatorInterface
{
    public function __construct(
        private string $date
    )
    {
    }

    public function validate(): bool
    {
        $data = \DateTime::createFromFormat('Y-m-d', $this->date);
        if (!$data || $data->format('Y-m-d') !== $this->date) {
            throw new \Exception('The date format is not valid.');

        }

        return true;
    }
}