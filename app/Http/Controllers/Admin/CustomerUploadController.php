<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Helpers\ListHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CustomerImportRequest;
use App\Http\Requests\Validations\CustomerUploadRequest;
use App\Models\State;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Rap2hpoutre\FastExcel\FastExcel;

class CustomerUploadController extends Controller
{
    private $failed_list = [];

    /**
     * Show upload form
     *
     * @return \Illuminate\Http\Response
     */
    public function showForm()
    {
        return view('admin.customer._upload_form');
    }

    /**
     * Upload the csv file and generate the review table
     *
     * @param  CustomerUploadRequest $request
     * @return \Illuminate\Http\Response
     */
    public function upload(CustomerUploadRequest $request)
    {
        $path = $request->file('customers')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $data[0] = array_map('strtolower', $data[0]);
        array_walk($data, function (&$a) use ($data) {
            $trimed = array_map('trim', $a);
            $a = array_combine($data[0], $trimed);
        });
        array_shift($data); // remove header column

        // Validations check for csv_import_limit
        if (count($data) > get_csv_import_limit()) {
            $message_bag = (new MessageBag)->add('error', trans('validation.upload_rows', ['rows' => get_csv_import_limit()]));

            return back()->withErrors($message_bag);
        }

        $rows = [];
        foreach ($data as $values) {
            $rows[] = clear_encoding_str($values);
        }

        return view('admin.customer.upload_review', compact('rows'));
    }

    /**
     * Perform import action
     *
     * @param  CustomerImportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function import(CustomerImportRequest $request)
    {
        if (config('app.demo') == true) {
            return redirect()->route('admin.admin.customer.index')->with('warning', trans('messages.demo_restriction'));
        }

        // Reset the Failed list
        $this->failed_list = [];

        foreach ($request->input('data') as $row) {
            $data = unserialize($row);

            if (!is_array($data)) { // Invalid data
                continue;
            }

            // Ignore if required info is not given
            if (!verifyRequiredDataForBulkUpload($data, 'customer')) {
                $this->pushIntoFailed($data, trans('help.missing_required_data'));
                continue;
            }

            // Validate email address
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->pushIntoFailed($data, trans('help.invalid_email'));
                continue;
            }

            // Ignore if the email is exist in the database
            $customer = Customer::select('email')->where('email', $data['email'])->first();
            if ($customer) {
                $this->pushIntoFailed($data, trans('help.email_already_exist'));
                continue;
            }

            // Create the customer and get it, If failed then insert into the ignored list
            if (!$this->createCustomer($data)) {
                $this->pushIntoFailed($data, trans('help.input_error'));
                continue;
            }
        }

        $request->session()->flash('success', trans('messages.imported', ['model' => trans('app.customers')]));

        $failed_rows = $this->getFailedList();

        if (!empty($failed_rows)) {
            return view('admin.customer.import_failed', compact('failed_rows'));
        }

        return redirect()->route('admin.admin.customer.index');
    }

    /**
     * Create Product
     *
     * @param  array $product
     * @return App\Product
     */
    private function createCustomer($data)
    {
        // Create the product
        $customer = Customer::create([
            'name' => $data['full_name'],
            'nice_name' => $data['nice_name'],
            'email' => $data['email'],
            'password' => $data['temporary_password'],
            'description' => $data['description'],
            'sex' => 'app.' . strtolower($data['sex']),
            'dob' => date('Y-m-d', strtotime($data['dob'])),
            'accepts_marketing' => strtoupper($data['accepts_marketing']) == 'TRUE' ? 1 : 0,
            'active' => strtoupper($data['active']) == 'TRUE' ? 1 : 0,
        ]);

        // Create addresses
        if ($data['primary_address_line_1']) {
            $customer->primaryAddress()->create($this->makeAddress($data, 'primary'));
        }
        if ($data['billing_address_line_1']) {
            $customer->billingAddress()->create($this->makeAddress($data, 'billing'));
        }
        if ($data['shipping_address_line_1']) {
            $customer->shippingAddress()->create($this->makeAddress($data, 'shipping'));
        }

        // Upload featured image
        if ($data['avatar_link']) {
            $customer->saveImageFromUrl($data['avatar_link']);
        }

        return $customer;
    }

    /**
     * downloadTemplate
     *
     * @return response response
     */
    public function downloadTemplate()
    {
        $pathToFile = public_path('csv_templates/customers.csv');

        return response()->download($pathToFile);
    }

    /**
     * [downloadFailedRows]
     *
     * @param  Excel  $excel
     */
    public function downloadFailedRows(Request $request)
    {
        foreach ($request->input('data') as $row) {
            $data[] = unserialize($row);
        }

        return (new FastExcel(collect($data)))->download('failed_rows.xlsx');
    }

    /**
     * return address array
     *
     * @param  array $data
     * @param  array $type Address Type
     * @return array $address
     */
    private function makeAddress($data, $type = 'primary')
    {
        $type = strtolower($type);

        $address = [
            'address_title' => ucfirst($type) . ' Address',
            'address_line_1' => $data[$type . '_address_line_1'],
            'address_line_2' => $data[$type . '_address_line_2'],
            'city' => $data[$type . '_address_city'],
            'zip_code' => $data[$type . '_address_zip_code'],
            'phone' => $data[$type . '_address_phone'],
            'latitude' => null,
            'longitude' => null,
        ];

        // Get the country id
        if ($data[$type . '_address_country_code']) {
            $country = DB::table('countries')->select(['id', 'name'])->where('iso_code', strtoupper($data[$type . '_address_country_code']))->first();
        }
        $address['country_id'] = isset($country) && !empty($country) ? $country->id : config('system_settings.address_default_country');

        // Get the state id
        if ($data[$type . '_address_state_name']) {
            $states = ListHelper::states($address['country_id']);
            $state_id = array_search(strtolower($data[$type . '_address_state_name']), array_map('strtolower', $states->toArray()));

            if (!$state_id) {
                $state_id = State::create(['name' => $data[$type . '_address_state_name'], 'country_name' => $country->name, 'country_id' => $country->id])->id;
            }
        }
        $address['state_id'] = isset($state_id) ? $state_id : config('system_settings.address_default_state');

        return $address;
    }

    /**
     * Push New value Into Failed List
     *
     * @param  array  $data
     * @param  string $reason
     * @return void
     */
    private function pushIntoFailed(array $data, $reason = null)
    {
        $row = [
            'data' => $data,
            'reason' => $reason,
        ];

        array_push($this->failed_list, $row);
    }

    /**
     * Return the failed list
     *
     * @return array
     */
    private function getFailedList()
    {
        return $this->failed_list;
    }
}
