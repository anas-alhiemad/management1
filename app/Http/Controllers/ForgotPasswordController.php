<?php

namespace App\Http\Controllers;
use App\Mail\SendCodeResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\ResetCodePassword;
class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users',
        ]);


        ResetCodePassword::where('email', $request->email)->delete();


        $data['code'] = mt_rand(100000, 999999);


        $codeData = ResetCodePassword::create($data);


        Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

        return response(['message' => trans('passwords.sent')], 200);
    }
}
