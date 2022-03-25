<?php

namespace Incevio\Package\Wallet\Test\Common\Models;

/**
 * Class Transaction.
 * @property null|string $bank_method
 */
class Transaction extends \Incevio\Package\Wallet\Models\Transaction
{
    /**
     * {@inheritdoc}
     */
    public function getFillable(): array
    {
        return array_merge($this->fillable, [
            'bank_method',
        ]);
    }
}
