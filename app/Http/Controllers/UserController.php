<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\User;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function isAdmin() {
        if (Auth::user()->is_admin()) {
            $res = 'admin';
        } else if (Auth::user()->is_manager()) {
            $res = 'manager';
        } else if (Auth::user()->is_user()) {
            $res = 'user';
        }

        return response()->json($res);
    }

    public function index() {
        if (!Auth::user()->is_admin()) {
            return redirect()->intended('dashboard');
        }

        return view('user');
    }

    public function readUsers() {
        if (!Auth::user()->is_admin()) {
            return redirect()->intended('dashboard');
        }

        $data = User::all();
        $res = [
            'data' => $data
        ];

        return response()->json($res);
    }

    public function saveUser(Request $request) {
        if (!Auth::user()->is_admin()) {
            return redirect()->intended('dashboard');
        }

        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:users',
                    'mobile' => 'required|unique:users|numeric|digits:11',
                    'password' => 'required',
                    'rePassword' => 'required',
                    'type' => 'required',
                    'status' => 'required'
        ]);

        if ($validator->fails()) {
            $res = ['status' => 'error', 'message' => $validator->errors()->first()];
            return response()->json($res);
        }


        $data = new User;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->mobile = $request->mobile;
        $data->password = bcrypt($request->password);
        $data->type = base64_decode($request->type);
        $data->is_active = base64_decode($request->status);
        $data->created_at = date("Y-m-d H:i:s");
        $data->save();

        if ($data->save()) {
            $res = ['status' => "success", 'message' => "User Save Successfully"];
        } else {
            $res = ['status' => "error", 'message' => "User Save Failed"];
        }

        return response()->json($res);
    }

    public function editUser(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => 'required',
        ]);

        if ($validator->fails()) {
            $res = ['status' => 'error', 'message' => $validator->errors()->first()];
            return response()->json($res);
        }

        $data = User::find($request->id);
        $data->type = base64_encode($data->type);
        $data->is_active = base64_encode($data->is_active);

        if (count($data) > 0) {
            $res = ['status' => 'success', 'message' => "User Data Found", 'data' => $data];
        } else {
            $res = ['status' => 'error', 'message' => "User Data Not Found"];
        }

        return response()->json($res);
    }

    public function updateUser(Request $request) {
        if (!Auth::user()->is_admin()) {
            return redirect()->intended('dashboard');
        }

        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,' . $request->id,
                    'mobile' => 'required|numeric|digits:11|unique:users,mobile,' . $request->id,
//                    'password' => 'required',
//                    'rePassword' => 'required',
                    'type' => 'required',
                    'status' => 'required'
        ]);

        if ($validator->fails()) {
            $res = ['status' => 'error', 'message' => $validator->errors()->first()];
            return response()->json($res);
        }

        $data = User::find($request->id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->mobile = $request->mobile;
        if (isset($request->password) && !is_null($request->password)) {
            $data->password = bcrypt($request->password);
        }
        $data->type = base64_decode($request->type);
        $data->is_active = base64_decode($request->status);
        $data->updated_at = date("Y-m-d H:i:s");
        $data->save();

        if ($data->save()) {
            $res = ['status' => "success", 'message' => "User Update Successfully"];
        } else {
            $res = ['status' => "error", 'message' => "User Update Failed"];
        }

        return response()->json($res);
    }

    public function deleteUser(Request $request) {
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

        $data = User::find($request->id);
        $data->delete();

        if (!$data->delete()) {
            $res = ['status' => "success", 'message' => "User Deleted Successfully"];
        } else {
            $res = ['status' => "error", 'message' => "User Delete Failed"];
        }

        return response()->json($res);
    }

}
