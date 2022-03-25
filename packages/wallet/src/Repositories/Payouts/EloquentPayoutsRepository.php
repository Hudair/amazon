<?php

namespace Incevio\Package\wallet\Repositories\Payouts;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Incevio\Package\Wallet\Models\Transaction;

class EloquentPayoutsRepository implements PayoutsRepository
{
    protected $transactions = "transactions";

    protected $month = 30;

    /**
     * Get Days From Dates;
     * */

    ##get Days Diff Form Date
    public function getDaysFromDate($fromDate, $toDate)
    {
        $diff = date_diff($fromDate, $toDate);

        return $diff->days;
    }

    /**
     * Get payouts for last 7 days;
     * */

    public function payouts(Carbon $date = null)
    {
       return Transaction::orderBy('id', 'desc')
           ->whereDate('created_at', '>=', $date ?? Carbon::today()->subDays(config('wallet::report.default', 7)) )
           ->get();
    }


    /**
     * Get payouts data by from date, to Date and status;
     * */
    public function morePayouts($packet){

        $data = Transaction::orderBy('created_at', 'desc');
        $data = self::searchQuery($data, $packet);

        return $data->get()->map(function ($item){
            return [
                'shop' => $item->payable->name,
                'date' => $item->created_at->toFormattedDateString(),
                'type' => $item->type,
                'description' => $item->meta['description'],
                'balance' => $item->balance,
                'amount' => $item->amount,
            ];
        });
    }

    /**
     * Payout Chart Data;
     * */
    public function chartPayouts(Carbon $date = null){
        return self::commonChartQuery()
            ->whereDate('created_at', '>=', $date ?? Carbon::today()->subDays(config('wallet::report.default', 7)))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * get more Chart data bby from date , to date and status:
     * */
    public function moreChartPayouts($packet)
    {
        $data = self::commonChartQuery($this->getDaysFromDate($packet['fromDate'], $packet['toDate']));
        $data = self::searchQuery($data, $packet);

        return $data->orderBy('created_at', 'desc')->get();
    }


    /**
     * Chart Common Query
     * */
    public function commonChartQuery($days = 30) : object
    {
        $query =  DB::table($this->transactions)
            ->select(
                DB::raw('(CASE WHEN type ="'.Transaction::TYPE_WITHDRAW.'" THEN SUM(amount) ELSE 0 END) as withdraw'),
                DB::raw('(CASE WHEN type ="'.Transaction::TYPE_DEPOSIT.'" THEN SUM(amount) ELSE 0 END) as deposit')
            );

        if ($days > $this->month){
            $query = $query->addSelect(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as date'));
        }
        else{
            $query = $query->addSelect(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") as date'));
        }

        return $query->groupBy('date', 'type')->orderBy('date', 'asc');
    }

    /**
     * Common Search Chart Common Query
     * */
    public function searchQuery($query, $packet)
    {
        if ($packet['fromDate'] !== null){
            $query->whereDate('created_at', '>=', $packet['fromDate']);
        }

        if ($packet['toDate'] !== null){
            $query->whereDate('created_at', '<=', $packet['toDate']);
        }

        if ($packet['status'] !== null){
            $query->where('approved', $packet['status']);
        }

        if ($packet['payoutType'] !== null){
            $query->where('type', $packet['payoutType']);
        }

        return $query;
    }

}