<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Session;
use Mail;
use App\Mail\SendForgotPass;
class AuthController extends Controller
{
    public function login()
    {
        Auth::guard('web')->logout();
        // dd(Session::all());
        return view('auth.login');
    }

    public function postSignin(Request $request)
    {

        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            if(Auth::guard('web')->user()->status == '1')
            {
                return redirect()->to('quotation');
            }else{
                Auth::guard('web')->logout();
                Session::flash('message', "message_login_status");
                return redirect()->to('/login')->withInput($request->input());
            }
        }
        Session::flash('message', "loginfalse");

        return redirect()->to('/login')->withInput($request->input());
    }

    public function create(Request $request)
    {
        $data = new User();
        $data->name = 'admin account';
        $data->role_id = 2;
        $data->email = 'adminaccount@edispro.com';
        $data->password = Hash::make('12345678');
        $data->status = 1;
        $data->created_at = Carbon::now();
        $data->created_by = '1';
        $data->save();
        // dd($data);
        // $crate['fast_name']
        // return User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        // ]);
    }

    public function forgotpassword(Request $request)
    {
        return view('auth.forgotpassword');
    }

    public function updatepassword(Request $request)
    {
        $strrandom = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $newPass =  substr(str_shuffle(str_repeat($strrandom, 5)), 0, 8);
        $updatepass = User::where('email',$request->email)->first();
        $updatepass->password = Hash::make($newPass);
        $updatepass->save();
        $updatepass->newpass = $newPass;
        Mail::to(trim($request->email))->send(new SendForgotPass($updatepass));
        Session::flash('message', "message_chang_pass");
        return redirect()->to('/login');
    }

    public function checkmail(Request $request)
    {
        if($request->ajax())
        {
            $check = User::where('email',$request->email)->first();
            if(!$check){
                return response()->json(['status'=>false,'msg'=>'ไม่พบอีเมลนี้ในระบบ'],200);
            }else{
                return response()->json(['status'=>true],200);
            }
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->to('login');
    }


}
