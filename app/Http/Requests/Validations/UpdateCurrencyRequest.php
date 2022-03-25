<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;

class UpdateCurrencyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $currency = $this->route('currency');

        return [
           'name' => 'required|string',
           'priority' => 'nullable|numeric',
           'active' => 'required',
           'iso_code' => 'required|size:3|unique:currencies,iso_code,'.$currency->id,
           'iso_numeric' => 'required|size:3|unique:currencies,iso_numeric,'.$currency->id,
           'symbol' => 'required',
           'subunit' => 'required',
           'decimal_mark' => 'required',
           'thousands_separator' => 'required',
           'symbol_first' => 'required',
           'subunit_to_unit' => 'required|numeric',
           'smallest_denomination' => 'required|numeric',
           'subunit_to_unit' => 'required|numeric',
        ];
    }
}
