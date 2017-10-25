<?php

namespace App\Http\Controllers;

use App\Confiq;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Product;
use App\Purchase;
use App\Customer;
use App\Order;
use App\OrderDetails;
use PDF;

class InvoiceController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $data = Order::select('orders.*', 'customers.mobile', 'customers.name')
                        ->join('customers', 'orders.customer_id', '=', 'customers.id')
                        ->orderBy('id', 'des')->get();

        $i = 1;
        foreach ($data as $key => $item) {
            //Assign serial number
            $data[$key]->sl = $i++;
            //Status human readable
            if ($item->status == 1) {
                $data[$key]->status = 'Paid';
            } else {
                $data[$key]->status = 'Unpaid';
            }
            //Discount value assign
            if (isset($item->discount) && !is_null($item->discount)) {
                $data[$key]->discount = $item->discount;
            } else {
                $data[$key]->discount = 0;
            }
        }

        $res = [
            'invoiceData' => $data
        ];

        return view('invoice', $res);
    }

    public function newInvoice() {
        $data = [
            'products' => Product::all()
        ];

        return view('newInvoice', $data);
    }

    public function getCustomer(Request $request) {
        $this->validate($request, [
            'mobile' => 'required'
        ]);

        $customer = Customer::select('id', 'name', 'mobile', 'address')
                        ->where('mobile', $request->mobile)->first();

        if (count($customer) > 0) {
            return ['status' => "success", 'message' => "Customer data found", 'data' => $customer];
        } else {
            return ['status' => "error", 'message' => "Customer data not found"];
        }
    }

    public function checkStock(Request $request) {
        //Get total product
        $totalPurchase = Purchase::select(DB::raw('SUM(qty) as total_product'))
                ->where('product_id', $request->productId)
                ->first();

        if (isset($totalPurchase) && !is_null($totalPurchase)) {
            $totalPurchaseAmount = $totalPurchase->total_product;
        } else {
            $totalPurchaseAmount = 0;
        }

        //Get total sell
        $totalSell = OrderDetails::select('products.name', DB::raw('SUM(qty) as total_sell'))
                ->join('products', 'products.id', '=', 'order_details.product_id')
                ->where('product_id', $request->productId)
                ->groupBy('products.name')
                ->first();

        if (isset($totalSell) && !is_null($totalSell)) {
            $totalSellAmount = $totalSell->total_sell;
        } else {
            $totalSellAmount = 0;
        }

        $stockPosition = $totalPurchaseAmount - $totalSellAmount;
        //Check stock position
        if (($request->qty > $stockPosition) || $stockPosition < 0) {
            $res = ['status' => "error", 'message' => $totalSell['name'] . ' ' . 'Product stock is' . ' ' . $stockPosition];
        } else {
            $res = ['status' => "success", 'message' => 'Product stock is not limit over'];
        }

        return $res;
    }

    public function createInvoice(Request $request) {
        $this->validate($request, [
            'mobile' => 'required',
            'name' => 'required',
            'address' => 'required',
            'product.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'quantity.*' => 'required|numeric',
        ]);

        DB::beginTransaction();
        //Check exist customer
        $existCustomer = $this->getCustomer($request);
        if ($existCustomer['status'] == 'error') {
            //Insert new customer
            $customer_data = new Customer;
            $customer_data->mobile = $request->mobile;
            $customer_data->name = $request->name;
            $customer_data->address = $request->address;
            $customer_data->created_by = Auth::id();
            $customer_data->created_at = date("Y-m-d H:i:s");
            $customer = $customer_data->save();
            if (!$customer) {
                DB::rollBack();
                return redirect('newInvoice')->with('status', 'Customer Data Save Failed');
            }
            $customerId = $customer_data->id;
        } else {
            $customerId = $existCustomer['data']['id'];
        }

        //Order data
        $lastInvoiceId = Order::orderBy('id', 'desc')->pluck('id')->first();
        $newInvoiceId = $lastInvoiceId + 1;
        $invoiceId = date("Ymd") . '-' . $newInvoiceId;

        $n = 0;
        $total = [];
        $totalDiscount = [];
        foreach ($request->product as $products) {
            $amount = ($request->quantity[$n]) * ($request->price[$n]);
            $discount = $request->discount[$n];
            array_push($total, $amount);
            array_push($totalDiscount, $discount);
            $n++;
        }

        $order_data = new Order;
        $order_data->invoice_id = $invoiceId;
        $order_data->amount = array_sum($total) - array_sum($totalDiscount);
        $order_data->discount = array_sum($totalDiscount);
        $order_data->customer_id = $customerId;
        $order_data->status = (isset($request->status) && $request->status == 'on' ? 0 : 1);
        $order_data->created_by = Auth::id();
        $order_data->created_at = date("Y-m-d H:i:s");
        $order = $order_data->save();

        if (!$order) {
            DB::rollBack();
            return redirect('newInvoice')->with('status', 'Order Data Save Failed');
        }

        //Order details data
        $i = 0;
        foreach ($request->product as $products) {
            //Stock position check
            $request->productId = $products;
            $request->qty = $request->quantity[$i];
            $checkStock = $this->checkStock($request);
            if ($checkStock['status'] == 'error') {
                DB::rollBack();
                return redirect('newInvoice')->with('status', 'Stock position over');
            }

            //Value assign for save
            $orderDetails_data = new OrderDetails;
            $orderDetails_data->order_id = $order_data->id;
            $orderDetails_data->product_id = $products;
            $orderDetails_data->description = $request->description[$i];
            $orderDetails_data->price = $request->price[$i];
            $orderDetails_data->qty = $request->quantity[$i];
            $orderDetails_data->discount = (isset($request->discount[$i]) && !is_null($request->discount[$i]) ? $request->discount[$i] : 0);
            $orderDetails_data->created_by = Auth::id();
            $orderDetails_data->created_at = date("Y-m-d H:i:s");
            $orderDetails = $orderDetails_data->save();
            $i++;

            if (!$orderDetails) {
                DB::rollBack();
                return redirect('newInvoice')->with('status', 'Order Details Data Save Failed');
            }
        }

        DB::commit();
        return redirect('invoice')->with('status', 'Invoice Created Successfully');
    }

    public function editInvoice($id) {
        $invoiceId = base64_decode($id);
        //Get customer details
        $customer = OrderDetails::select('customers.name as cus_name', 'customers.mobile as cus_mobile', 'customers.address as cus_add', 'orders.id as old_order_id', 'orders.status as paid_status')
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->where('order_details.order_id', $invoiceId)
                ->first();

        //Get order details
        $orderDetails = OrderDetails::select('order_details.*')
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->where('order_details.order_id', $invoiceId)
                ->get();

        $sl = 0;
        $total = [];
        foreach ($orderDetails as $key => $value) {
            $sl++;
            $orderDetails[$key]->sl = $sl;
            $orderDetails[$key]->amount = ($value->price * $value->qty) - ($value->discount);
            array_push($total, $value->amount);
        }

        //Get all products
        $products = Product::get();

        $data = [
            'customer' => $customer,
            'orderDetails' => $orderDetails,
            'products' => $products,
            'total' => array_sum($total),
        ];
        return view('editInvoice', ['data' => $data]);
    }

    public function updateInvoice(Request $request) {
        DB::beginTransaction();
        //Delete order details data
        $delOrderDetails = OrderDetails::where('order_id', $request->old_order_id)->delete();
        if (!$delOrderDetails) {
            DB::rollBack();
            return redirect('invoice')->with('status', 'Order Details Data delete Failed');
        }

        //Delete order data
        $delOrder = Order::where('id', $request->old_order_id)->delete();
        if (!$delOrder) {
            DB::rollBack();
            return redirect('invoice')->with('status', 'Order Data Delete Failed');
        }

        //Insert new invoice data
        $this->createInvoice($request);

        DB::commit();
        return redirect('invoice')->with('status', 'Invoice Updated Successfully');
    }

    public function printInvoice($id) {
        $invoiceId = base64_decode($id);
        //Get customer details
        $customer = OrderDetails::select('customers.name as cus_name', 'customers.mobile as cus_mobile', 'customers.address as cus_add', 'orders.id as old_order_id', 'orders.status as paid_status')
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->where('order_details.order_id', $invoiceId)
                ->first();

        //Get order details
        $orderDetails = OrderDetails::select('order_details.*', 'products.name as pro_name')
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->join('products', 'order_details.product_id', '=', 'products.id')
                ->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->where('order_details.order_id', $invoiceId)
                ->get();

        $sl = 0;
        $total = [];
        foreach ($orderDetails as $key => $value) {
            $sl++;
            $orderDetails[$key]->sl = $sl;
            $orderDetails[$key]->amount = ($value->price * $value->qty) - ($value->discount);
            array_push($total, $value->amount);
        }

        $data = [
            'customer' => $customer,
            'orderDetails' => $orderDetails,
            'orderData' => Order::find($invoiceId)->first(),
            'shopInfo' => Confiq::first(),
            'total' => array_sum($total),
        ];

        $pdf = PDF::loadView('invoicePDF', ['data' => $data]);
        return $pdf->stream();
    }

}
