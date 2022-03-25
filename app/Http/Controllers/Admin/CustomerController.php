<?php

namespace App\Http\Controllers\Admin;

use App\Common\Authorizable;
use App\Events\Customer\PasswordUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\AdminCustomerUpdatePasswordRequest as UpdatePasswordRequest;
use App\Http\Requests\Validations\CreateCustomerRequest;
use App\Http\Requests\Validations\UpdateCustomerRequest;
use App\Repositories\Customer\CustomerRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class CustomerController extends Controller
{
    use Authorizable;

    private $model;

    private $customer;

    /**
     * construct
     */
    public function __construct(CustomerRepository $customer)
    {
        parent::__construct();

        $this->model = trans('app.model.customer');

        $this->customer = $customer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $customers = $this->customer->all();

        $trashes = $this->customer->trashOnly();

        return view('admin.customer.index', compact('trashes'));
    }

    // Function will process the ajax request to fetch data
    public function getCustomers(Request $request)
    {
        $customers = $this->customer->all();

        return Datatables::of($customers)
            ->editColumn('checkbox', function ($customer) {
                return view('admin.partials.actions.customer.checkbox', compact('customer'));
            })
            ->addColumn('option', function ($customer) {
                return view('admin.partials.actions.customer.options', compact('customer'));
            })
            ->addColumn('image', function ($customer) {
                return view('admin.partials.actions.customer.image', compact('customer'));
            })
            ->editColumn('nice_name', function ($customer) {
                return view('admin.partials.actions.customer.nice_name', compact('customer'));
            })
            ->editColumn('name', function ($customer) {
                return view('admin.partials.actions.customer.full_name', compact('customer'));
            })
            ->editColumn('orders_count', function ($customer) {
                return view('admin.partials.actions.customer.orders_count', compact('customer'));
            })
            ->rawColumns(['image', 'nice_name', 'name', 'orders_count', 'checkbox', 'option'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customer._create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCustomerRequest $request)
    {
        $this->customer->store($request);

        return back()->with('success', trans('messages.created', ['model' => $this->model]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $custidomer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = $this->customer->find($id);

        return view('admin.customer._show', compact('customer'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addresses($id)
    {
        $customer = $this->customer->find($id);

        $data = [
            'addressable_type' => 'customer',
            'addressable' => $customer,
            'addresses' => $this->customer->addresses($customer)
        ];

        return view('address.show', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile($id)
    {
        $customer = $this->customer->profile($id);

        return view('admin.customer.profile', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = $this->customer->find($id);

        return view('admin.customer._edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.customers', 1)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->customer->update($request, $id);

        return back()->with('success', trans('messages.updated', ['model' => $this->model]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showChangePasswordForm(Request $request, $id)
    {
        $customer = $this->customer->find($id);

        return view('admin.customer._change_password', compact('customer'));
    }

    /**
     * Update login password only.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(UpdatePasswordRequest $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.customers', 1)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $customer = $this->customer->update($request, $id);

        event(new PasswordUpdated($customer));

        return back()->with('success', trans('messages.password_updated'));
    }

    /**
     * Trash the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.customers', 1)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->customer->trash($id);

        return back()->with('success', trans('messages.trashed', ['model' => $this->model]));
    }

    /**
     * Restore the specified resource from soft delete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        $this->customer->restore($id);

        return back()->with('success', trans('messages.restored', ['model' => $this->model]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->customer->destroy($id);

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massTrash(Request $request)
    {
        if (config('app.demo') == true) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->customer->massTrash($request->ids);

        if ($request->ajax()) {
            return response()->json([
                'success' => trans('messages.trashed', ['model' => $this->model])
            ]);
        }

        return back()->with('success', trans('messages.trashed', ['model' => $this->model]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massRestore(Request $request)
    {
        $this->customer->massRestore($request->ids);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.restored', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.restored', ['model' => $this->model]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request)
    {
        if (config('app.demo') == true) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->customer->massDestroy($request->ids);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }

    /**
     * Empty the Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyTrash(Request $request)
    {
        $this->customer->emptyTrash($request);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }
}
