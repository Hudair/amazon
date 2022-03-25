<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\InventoryImportRequest;
use App\Http\Requests\Validations\InventoryUploadRequest;
use App\Jobs\ProcessInventoryCsvBulkUpload as ProcessUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Rap2hpoutre\FastExcel\FastExcel;

class InventoryUploadController extends Controller
{
    /**
     * Show upload form
     *
     * @return \Illuminate\Http\Response
     */
    public function showForm()
    {
        return view('admin.inventory._upload_form');
    }

    /**
     * Upload the csv file and generate the review table
     *
     * @param  InventoryUploadRequest $request
     * @return \Illuminate\Http\Response
     */
    public function upload(InventoryUploadRequest $request)
    {
        $path = $request->file('inventories')->getRealPath();
        $records = array_map('str_getcsv', file($path));

        // Validations check for csv_import_limit
        if ((count($records) - 1) > get_csv_import_limit()) {
            $err = (new MessageBag)->add('error', trans('validation.upload_rows', ['rows' => get_csv_import_limit()]));

            return back()->withErrors($err);
        }

        // Get field names from header column
        $fields = array_map('strtolower', $records[0]);

        // Remove the header column
        array_shift($records);

        $rows = [];
        foreach ($records as $record) {
            if (count($fields) != count($record)) {
                $err = (new MessageBag)->add('error', trans('validation.csv_upload_invalid_data'));

                return back()->withErrors($err);
            }

            // Decode unwanted html entities
            $record =  array_map("html_entity_decode", $record);

            // Set the field name as key
            $temp = array_combine($fields, $record);

            // Get the clean data
            $rows[] = clear_encoding_str($temp);
        }

        return view('admin.inventory.upload_review', compact('rows'));
    }

    /**
     * Perform import action
     *
     * @param  InventoryImportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function import(InventoryImportRequest $request)
    {
        if (config('app.demo') == true) {
            return redirect()->route('admin.stock.inventory.index')
                ->with('warning', trans('messages.demo_restriction'));
        }

        ProcessUpload::dispatch(Auth::user(), $request->input('data'));

        return redirect()->route('admin.stock.inventory.index')
            ->with('global_notice', trans('messages.csv_import_process_started'));
    }

    /**
     * downloadTemplate
     *
     * @return response response
     */
    public function downloadTemplate()
    {
        $pathToFile = public_path('csv_templates/inventories.csv');

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

        $path = storage_path('failed_rows.xlsx');

        return (new FastExcel(collect($data)))->download($path);
    }
}
