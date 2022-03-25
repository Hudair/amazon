<?php

namespace Incevio\Package\Wallet\Objects;

use Incevio\Package\Wallet\Interfaces\Mathable;
// use Incevio\Package\Wallet\Interfaces\Wallet;
use Incevio\Package\Wallet\Models\Transaction;
use Ramsey\Uuid\Uuid;

class Operation
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var int
     */
    protected $balance;

    /**
     * @var null|array
     */
    protected $meta;

    /**
     * @var bool
     */
    protected $confirmed;

    /**
     * @var bool
     */
    protected $approved;

    /**
     * @var Wallet
     */
    protected $wallet;

    /**
     * Transaction constructor.
     * @throws
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return float|int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return float|int
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @return array|null
     */
    public function getMeta(): ?array
    {
        return $this->meta;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->approved;
    }

    /**
     * @param string $type
     * @return static
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param int $amount
     * @return static
     */
    public function setAmount($amount): self
    {
        $this->amount = app(Mathable::class)->round($amount, 2);

        return $this;
    }

    /**
     * @param array|null $meta
     * @return static
     */
    public function setMeta(?array $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @param bool $confirmed
     * @return static
     */
    public function setConfirmed(bool $confirmed): self
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    public function setBalance($remainingBalance): self
    {
        $this->balance = $remainingBalance;

        return $this;
    }

    /**
     * @param bool $approve
     * @return static
     */
    public function setApproved(bool $approve): self
    {
        $this->approved = $approve;

        return $this;
    }

    /**
     * @return Wallet Model
     */
    public function getWallet()
    {
        return $this->wallet;
    }

    /**
     * @param Wallet $wallet
     * @return static
     */
    public function setWallet($wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }

    /**
     * @return Transaction Model
     */
    public function create(): Transaction
    {
        return $this->getWallet()->transactions()->create($this->toArray());
    }

    /**
     * @return array
     * @throws
     */
    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'wallet_id' => $this->getWallet()->getKey(),
            'uuid' => $this->getUuid(),
            'confirmed' => $this->isConfirmed(),
            'approved' => $this->isApproved(),
            'amount' => $this->getAmount(),
            'balance' => $this->getBalance(),
            'meta' => $this->getMeta(),
        ];
    }
}
