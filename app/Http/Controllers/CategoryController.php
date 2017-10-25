<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Category;
use App\Product;

class CategoryController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('category');
    }

    public function readCategories() {
        $data = Category::all();
        $res = [
            'data' => $data
        ];

        return response()->json($res);
    }

    public function saveCategory(Request $request) {
        if (!Auth::user()->is_admin()) {
            return redirect()->intended('dashboard');
        }

        $validator = Validator::make($request->all(), [
                    'name' => 'required',
        ]);

        if ($validator->fails()) {
            $res = ['status' => 'error', 'message' => $validator->errors()->first()];
            return response()->json($res);
        }

        $data = new Category;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->created_at = date("Y-m-d H:i:s");
        $data->save();

        if ($data->save()) {
            $res = ['status' => "success", 'message' => "Category Save Successfully"];
        } else {
            $res = ['status' => "error", 'message' => "Category Save Failed"];
        }

        return response()->json($res);
    }

    public function editCategory(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required',
        ]);

        if ($validator->fails()) {
            $res = ['status' => 'error', 'message' => $validator->errors()->first()];
            return response()->json($res);
        }

        $data = Category::find($request->id);

        if (count($data) > 0) {
            $res = ['status' => 'success', 'message' => "Category Data Found", 'data' => $data];
        } else {
            $res = ['status' => 'error', 'message' => "Category Data Not Found"];
        }

        return response()->json($res);
    }

    public function updateCategory(Request $request) {
        if (!Auth::user()->is_admin()) {
            return redirect()->intended('dashboard');
        }

        $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'name' => 'required',
        ]);

        if ($validator->fails()) {
            $res = ['status' => 'error', 'message' => $validator->errors()->first()];
            return response()->json($res);
        }

        $data = Category::find($request->id);
        $data->name = $request->name;
        $data->description = $request->description;
        $data->updated_at = date("Y-m-d H:i:s");
        $data->save();

        if ($data->save()) {
            $res = ['status' => "success", 'message' => "Category Update Successfully"];
        } else {
            $res = ['status' => "error", 'message' => "Category Update Failed"];
        }

        return response()->json($res);
    }

    public function deleteCategory(Request $request) {
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

        //Check already exist product
        $product = Product::select('products.id', 'products.name', 'products.categories_id', 'categories.name as cat_name')
                ->join('categories', 'categories.id', '=', 'products.categories_id')
                ->where('products.categories_id', '=', $request->id)
                ->first();

        if (count($product) > 0) {
            $res = ['status' => "error", 'message' => "Category ($product->cat_name) already used in product($product->name).Please remove/change product's category first"];
            return response()->json($res);
        }

        $data = Category::find($request->id);
        $data->delete();

        if (!$data->delete()) {
            $res = ['status' => "success", 'message' => "Category Deleted Successfully"];
        } else {
            $res = ['status' => "error", 'message' => "Category Delete Failed"];
        }

        return response()->json($res);
    }

}
