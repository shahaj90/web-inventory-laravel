<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Product;
use App\Category;
use App\Purchase;

class ProductController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $data = [
            'catData' => Category::select('id', 'name')->get(),
            'productData' => Product::select('id', 'name')->get(),
        ];
        return view('product', $data);
    }

    //Product area
    public function readProducts() {
        $data = Product::select('products.*', 'categories.name as cat_name')
                ->join('categories', 'categories.id', '=', 'products.categories_id')
                ->get();
        $res = [
            'data' => $data
        ];

        return response()->json($res);
    }

    public function saveProduct(Request $request) {
        if (!Auth::user()->is_admin()) {
            return redirect()->intended('dashboard');
        }

        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'categoryName' => 'required',
        ]);

        if ($validator->fails()) {
            $res = ['status' => 'error', 'message' => $validator->errors()->first()];
            return response()->json($res);
        }

        $data = new Product;
        $data->name = $request->name;
        $data->categories_id = $request->categoryName;
        $data->description = $request->description;
        $data->created_by = Auth::id();
        $data->created_at = date("Y-m-d H:i:s");
        $data->save();

        if ($data->save()) {
            $res = ['status' => "success", 'message' => "Product Save Successfully"];
        } else {
            $res = ['status' => "error", 'message' => "Product Save Failed"];
        }

        return response()->json($res);
    }

    public function editProduct(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required',
        ]);

        if ($validator->fails()) {
            $res = ['status' => 'error', 'message' => $validator->errors()->first()];
            return response()->json($res);
        }

        $data = Product::find($request->id);

        if (count($data) > 0) {
            $res = ['status' => 'success', 'message' => "Product Data Found", 'data' => $data];
        } else {
            $res = ['status' => 'error', 'message' => "Product Data Not Found"];
        }

        return response()->json($res);
    }

    public function updateProduct(Request $request) {
        if (!Auth::user()->is_admin()) {
            return redirect()->intended('dashboard');
        }

        $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'name' => 'required',
                    'categoryName' => 'required',
        ]);

        if ($validator->fails()) {
            $res = ['status' => 'error', 'message' => $validator->errors()->first()];
            return response()->json($res);
        }

        $data = Product::find($request->id);
        $data->name = $request->name;
        $data->categories_id = $request->categoryName;
        $data->description = $request->description;
        $data->updated_by = Auth::id();
        $data->updated_at = date("Y-m-d H:i:s");
        $data->save();

        if ($data->save()) {
            $res = ['status' => "success", 'message' => "Product Update Successfully"];
        } else {
            $res = ['status' => "error", 'message' => "Product Update Failed"];
        }

        return response()->json($res);
    }

    public function deleteProduct(Request $request) {
        if (!Auth::user()->is_admin()) {
            return redirect()->intended('dashboard');
        }

        $validator = Validator::make($request->all(), [
                    'id' => 'required',
        ]);

        if ($validator->fails()) {
            $res = ['status' => 'error', 'message' => $validator->errors()->first()];
            return response()->json($res);
        }

        //Check already exist product in purchase
        $product = Purchase::select('products.name')
                ->join('products', 'products.id', '=', 'purchases.product_id')
                ->where('purchases.product_id', '=', $request->id)
                ->first();

        if (count($product) > 0) {
            $res = ['status' => "error", 'message' => "$product->name already used in purchase. Please remove/change product's from purchase first"];
            return response()->json($res);
            
        }

        $data = Product::find($request->id);
        $data->delete();

        if (!$data->delete()) {
            $res = ['status' => "success", 'message' => "Product Deleted Successfully"];
        } else {
            $res = ['status' => "error", 'message' => "Product Delete Failed"];
        }

        return response()->json($res);
    }

    //Purchase area
    public function readPurchases() {
        $data = Purchase::select('purchases.*', 'products.name as product_name', 'categories.name as cat_name')
                ->join('products', 'products.id', '=', 'purchases.product_id')
                ->join('categories', 'categories.id', '=', 'products.categories_id')
                ->get();
        $res = [
            'data' => $data
        ];

        return response()->json($res);
    }

    public function getProductCat(Request $request) {
        $data = Product::select('categories.name as catName')
                ->join('categories', 'categories.id', '=', 'products.categories_id')
                ->where('products.id', $request->id)
                ->first();

        if (count($data) > 0) {
            $res = ['status' => "success", 'message' => "Category name found", 'data' => $data];
        } else {
            $res = ['status' => "error", 'message' => "Category name not found"];
        }

        return response()->json($res);
    }

    public function savePurchase(Request $request) {
        if (!Auth::user()->is_admin()) {
            return redirect()->intended('dashboard');
        }

        $validator = Validator::make($request->all(), [
                    'date' => 'required|date',
                    'productName' => 'required|numeric',
                    'unitPrice' => 'required|numeric',
                    'qty' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $res = ['status' => 'error', 'message' => $validator->errors()->first()];
            return response()->json($res);
        }

        $data = new Purchase;
        $data->date = date('Y-m-d', strtotime($request->date));
        $data->product_id = $request->productName;
        $data->unit_price = $request->unitPrice;
        $data->qty = $request->qty;
        $data->description = $request->description;
        $data->created_by = Auth::id();
        $data->created_at = date("Y-m-d H:i:s");
        $data->save();

        if ($data->save()) {
            $res = ['status' => "success", 'message' => "Purchase Product Save Successfully"];
        } else {
            $res = ['status' => "error", 'message' => "Purchase Product Save Failed"];
        }

        return response()->json($res);
    }

    public function editPurchase(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required',
        ]);

        if ($validator->fails()) {
            $res = ['status' => 'error', 'message' => $validator->errors()->first()];
            return response()->json($res);
        }

        $data = Purchase::find($request->id);
        $request['id'] = $data->product_id;
        $catName = $this->getProductCat($request)->getData()->data;
        $data->date = date('d-m-Y', strtotime($data->date));
        $data->cat_name = $catName->catName;
        $data->total = $data->qty * $data->unit_price;

        if (count($data) > 0) {
            $res = ['status' => 'success', 'message' => "Purchase Product Data Found", 'data' => $data];
        } else {
            $res = ['status' => 'error', 'message' => "Purchase Product Data Not Found"];
        }

        return response()->json($res);
    }

    public function updatePurchase(Request $request) {
        if (!Auth::user()->is_admin()) {
            return redirect()->intended('dashboard');
        }

        $validator = Validator::make($request->all(), [
                    'date' => 'required|date',
                    'productName' => 'required|numeric',
                    'unitPrice' => 'required|numeric',
                    'qty' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $res = ['status' => 'error', 'message' => $validator->errors()->first()];
            return response()->json($res);
        }

        $data = Purchase::find($request->id);
        $data->date = date('Y-m-d', strtotime($request->date));
        $data->product_id = $request->productName;
        $data->unit_price = $request->unitPrice;
        $data->qty = $request->qty;
        $data->description = $request->description;
        $data->updated_by = Auth::id();
        $data->updated_at = date("Y-m-d H:i:s");
        $data->save();

        if ($data->save()) {
            $res = ['status' => "success", 'message' => "Purchase product Update Successfully"];
        } else {
            $res = ['status' => "error", 'message' => "Purchase product Update Failed"];
        }

        return response()->json($res);
    }

}
