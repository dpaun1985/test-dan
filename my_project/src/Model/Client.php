<?php
namespace App\Model;

class Client
{
    private \DateTimeImmutable $date;

    private int $clientId;

    private string $clientType;

    private string $action;

    private float $amount;

    private string $currency;

    /**
     * Client constructor.
     *
     * @param array $data
     * @throws \DateMalformedStringException
     */
    public function __construct( array $data )
    {
        $this->setDate(new \DateTimeImmutable($data[0]));
        $this->setClientId($data[1]);
        $this->setClientType($data[2]);
        $this->setAction($data[3]);
        $this->setAmount($data[4]);
        $this->setCurrency($data[5]);
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param \DateTimeImmutable $date
     * @return void
     */
    public function setDate(\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getClientId(): int
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     * @return void
     */
    public function setClientId(int $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getClientType(): string
    {
        return $this->clientType;
    }

    /**
     * @param string $clientType
     * @return void
     */
    public function setClientType(string $clientType): void
    {
        $this->clientType = $clientType;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return void
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return void
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return void
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }
}