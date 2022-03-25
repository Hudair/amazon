<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Common\Authorizable;
use App\Helpers\ListHelper;
use App\Events\Order\OrderCreated;
use App\Events\Order\OrderFulfilled;
use App\Events\Order\OrderUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CreateOrderRequest;
use App\Http\Requests\Validations\FulfillOrderRequest;
use App\Repositories\Order\OrderRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// use App\Services\PdfInvoice;
// use Konekt\PdfInvoice\InvoicePrinter;

class OrderController extends Controller
{
    use Authorizable;

    private $model_name;

    private $order;

    /**
     * construct
     */
    public function __construct(OrderRepository $order)
    {
        parent::__construct();
        $this->model_name = trans('app.model.order');
        $this->order = $order;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Route::is('admin.order.pickup')) {
            $fulfilment = Order::FULFILMENT_TYPE_PICKUP;
        } else {
            $fulfilment = Order::FULFILMENT_TYPE_DELIVER;
        }

        $orders = $this->order->all($fulfilment);

        $archives = $this->order->trashOnly();

        return view('admin.order.index', compact('orders', 'archives'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchCutomer()
    {
        return view('admin.order._search_customer');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['customer'] = $this->order->getCustomer($request->input('customer_id'));

        $data['cart_lists'] = $this->order->getCartList($request->input('customer_id'));

        if ($request->input('cart_id')) {
            $data['cart'] = $this->order->getCart($request->input('cart_id'));
        }

        return view('admin.order.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrderRequest $request)
    {
        $order = $this->order->store($request);

        event(new OrderCreated($order));

        return redirect()->route('admin.order.order.index')
            ->with('success', trans('messages.created', ['model' => $this->model_name]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = $this->order->find($id);

        $this->authorize('view', $order); // Check permission

        $address = $order->customer->primaryAddress();

        return view('admin.order.show', compact('order', 'address'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function invoice($id)
    {
        $order = $this->order->find($id);

        $this->authorize('view', $order); // Check permission

        $order->invoice('D'); // Download the invoice
    }

    /**
     * Show the fulfillment form for the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fulfillment($id)
    {
        $order = $this->order->find($id);

        $this->authorize('fulfill', $order); // Check permission

        $carriers = ListHelper::carriers($order->shop_id);

        return view('admin.order._fulfill', compact('order', 'carriers'));
    }

    public function deliveryBoys($id)
    {
        $order = $this->order->find($id);

        $deliveryboys = ListHelper::deliveryBoys($order->shop_id);

        return view('admin.order._assign_delivery_boy', compact('deliveryboys', 'order'));
    }

    public function assignDeliveryBoy(Request $request, $id)
    {
        $order = $this->order->find($id);

        $order->delivery_boy_id = $request->delivery_boy_id;
        $order->save();

        return back()->with('success', trans('messages.created', ['model' => $this->model_name]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = $this->order->find($id);

        $this->authorize('fulfill', $order); // Check permission

        return view('admin.order._edit', compact('order'));
    }

    /**
     * Fulfill the order
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function fulfill(FulfillOrderRequest $request, $id)
    {
        $order = $this->order->find($id);

        $this->authorize('fulfill', $order); // Check permission

        $this->order->fulfill($request, $order);

        event(new OrderFulfilled($order, $request->filled('notify_customer')));

        if (config('shop_settings.auto_archive_order') && $order->isPaid()) {
            $this->order->trash($id);

            return redirect()->route('admin.order.order.index')
                ->with('success', trans('messages.fulfilled_and_archived'));
        }

        return back()->with('success', trans('messages.updated', ['model' => $this->model_name]));
    }

    /**
     * updateOrderStatus the order
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $order = $this->order->find($id);

        $this->authorize('fulfill', $order); // Check permission

        $this->order->updateOrderStatus($request, $order);

        event(new OrderUpdated($order, $request->filled('notify_customer')));

        return back()->with('success', trans('messages.updated', ['model' => $this->model_name]));
    }

    public function adminNote($id)
    {
        $order = $this->order->find($id);

        $this->authorize('fulfill', $order); // Check permission

        return view('admin.order._edit_admin_note', compact('order'));
    }

    public function saveAdminNote(Request $request, $id)
    {
        $order = $this->order->find($id);

        // $this->authorize('fulfill', $order); // Check permission

        $this->order->updateAdminNote($request, $order);

        return back()->with('success', trans('messages.updated', ['model' => $this->model_name]));
    }

    /**
     * Trash the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Order  $order
     * @return \Illuminate\Http\Response
     */
    public function archive(Request $request, $id)
    {
        $this->order->trash($id);

        return redirect()->route('admin.order.order.index')
            ->with('success', trans('messages.archived', ['model' => $this->model_name]));
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
        $this->order->restore($id);

        return back()->with('success', trans('messages.restored', ['model' => $this->model_name]));
    }

    /**
     * Toggle Payment Status of the given order, Its uses the ajax middleware
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function togglePaymentStatus(Request $request, $id)
    {
        if (Auth::user()->isFromMerchant() && !vendor_get_paid_directly()) {
            return back()->with('warning', trans('messages.failed', ['model' => $this->model_name]));
        }

        $order = $this->order->find($id);

        $this->authorize('fulfill', $order); // Check permission

        if ($order->isPaid()) {
            $order->markAsUnpaid();
        } else {
            $order->markAsPaid();
        }

        return back()->with('success', trans('messages.updated', ['model' => $this->model_name]));
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
        $this->order->destroy($id);

        return back()->with('success', trans('messages.deleted', ['model' => $this->model_name]));
    }

    /**
     * Empty the Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyTrash(Request $request)
    {
        $this->order->emptyTrash($request);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model_name])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model_name]));
    }
}
