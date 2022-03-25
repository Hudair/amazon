<?php

namespace App\Repositories\Tax;

use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use App\Models\Tax;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EloquentTax extends EloquentRepository implements BaseRepository, TaxRepository
{
    protected $model;

    public function __construct(Tax $tax)
    {
        $this->model = $tax;
    }

    public function all()
    {
        if (!Auth::user()->isFromPlatform()) {
            return $this->model->mine()->with('country', 'state')->get();
        }

        return $this->model->with('country', 'state')->get();
    }

    public function trashOnly()
    {
        if (!Auth::user()->isFromPlatform()) {
            return $this->model->mine()->onlyTrashed()->get();
        }

        return parent::trashOnly();
    }
}
