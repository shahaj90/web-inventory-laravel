<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Confiq;

class SettingController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        if (!Auth::user()->is_admin()) {
            return redirect()->intended('dashboard');
        }

        $data = [
            'conf_data' => Confiq::first()
        ];

        return view('setting', $data);
    }

    public function confiqSave(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
        ]);

        $conf_data = Confiq::find($request->id);
        $conf_data->name = $request->name;
        $conf_data->address = $request->address;
        $conf_data->email = $request->email;
        $conf_data->mobile = $request->mobile;
        $conf_data->updated_at = date("Y-m-d H:i:s");
        $conf_data->save();

        if ($conf_data->save()) {
            return redirect('setting')->with('success', 'Data Save successfully');
        } else {
            return redirect('setting')->with('error', 'Data Save successfully');
        }
    }

}
