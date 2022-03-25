<?php

namespace App\Services;

use Closure;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

/**
 * Class DbService.
 * @codeCoverageIgnore
 */
class DbService
{
    /**
     * @return ConnectionInterface
     */
    public static function connection(): ConnectionInterface
    {
        return DB::connection(config('wallet.database.connection'));
    }

    /**
     * Execute a Closure within a transaction.
     *
     * @param Closure $callback
     * @param int $attempts
     * @return mixed
     *
     * @throws \Throwable
     */
    public static function transaction(Closure $callback, $attempts = 1)
    {
        if (self::connection()->transactionLevel()) {
            return $callback(__CLASS__);
        }

        return self::connection()->transaction($callback, $attempts);
    }

    /**
     * Get a new raw query expression.
     *
     * @param mixed $value
     * @return Expression
     */
    public static function raw($value): Expression
    {
        return self::connection()->raw($value);
    }
}
