<?php

namespace Incevio\Package\Wallet\Test;

use Incevio\Package\Wallet\Interfaces\Mathable;
use Incevio\Package\Wallet\Interfaces\Rateable;
use Incevio\Package\Wallet\Interfaces\Storable;
use Incevio\Package\Wallet\Objects\Bring;
use Incevio\Package\Wallet\Objects\Cart;
use Incevio\Package\Wallet\Objects\EmptyLock;
use Incevio\Package\Wallet\Objects\Operation;
use Incevio\Package\Wallet\Services\CommonService;
use App\Services\DbService;
use Incevio\Package\Wallet\Services\ExchangeService;
use Incevio\Package\Wallet\Services\LockService;
use Incevio\Package\Wallet\Services\WalletService;
use Incevio\Package\Wallet\Test\Common\Models\Transaction;
use Incevio\Package\Wallet\Test\Common\Models\Transfer;
use Incevio\Package\Wallet\Test\Common\Models\Wallet;

class SingletonTest extends TestCase
{
    /**
     * @param string $object
     * @return string
     */
    protected function getRefId(string $object): string
    {
        return spl_object_hash(app($object));
    }

    /**
     * @return void
     */
    public function testBring(): void
    {
        self::assertNotEquals($this->getRefId(Bring::class), $this->getRefId(Bring::class));
    }

    /**
     * @return void
     */
    public function testCart(): void
    {
        self::assertNotEquals($this->getRefId(Cart::class), $this->getRefId(Cart::class));
    }

    /**
     * @return void
     */
    public function testEmptyLock(): void
    {
        self::assertNotEquals($this->getRefId(EmptyLock::class), $this->getRefId(EmptyLock::class));
    }

    /**
     * @return void
     */
    public function testOperation(): void
    {
        self::assertNotEquals($this->getRefId(Operation::class), $this->getRefId(Operation::class));
    }

    /**
     * @return void
     */
    public function testRateable(): void
    {
        self::assertEquals($this->getRefId(Rateable::class), $this->getRefId(Rateable::class));
    }

    /**
     * @return void
     */
    public function testStorable(): void
    {
        self::assertEquals($this->getRefId(Storable::class), $this->getRefId(Storable::class));
    }

    /**
     * @return void
     */
    public function testMathable(): void
    {
        self::assertEquals($this->getRefId(Mathable::class), $this->getRefId(Mathable::class));
    }

    /**
     * @return void
     */
    public function testTransaction(): void
    {
        self::assertNotEquals($this->getRefId(Transaction::class), $this->getRefId(Transaction::class));
    }

    /**
     * @return void
     */
    public function testTransfer(): void
    {
        self::assertNotEquals($this->getRefId(Transfer::class), $this->getRefId(Transfer::class));
    }

    /**
     * @return void
     */
    public function testWallet(): void
    {
        self::assertNotEquals($this->getRefId(Wallet::class), $this->getRefId(Wallet::class));
    }

    /**
     * @return void
     */
    public function testExchangeService(): void
    {
        self::assertEquals($this->getRefId(ExchangeService::class), $this->getRefId(ExchangeService::class));
    }

    /**
     * @return void
     */
    public function testCommonService(): void
    {
        self::assertEquals($this->getRefId(CommonService::class), $this->getRefId(CommonService::class));
    }

    /**
     * @return void
     */
    public function testWalletService(): void
    {
        self::assertEquals($this->getRefId(WalletService::class), $this->getRefId(WalletService::class));
    }

    /**
     * @return void
     */
    public function testDbService(): void
    {
        self::assertEquals($this->getRefId(DbService::class), $this->getRefId(DbService::class));
    }

    /**
     * @return void
     */
    public function testLockService(): void
    {
        self::assertEquals($this->getRefId(LockService::class), $this->getRefId(LockService::class));
    }
}
