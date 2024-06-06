<?php

namespace App\Http\Controllers;

use App\Mail\SendCodeResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\ResetCodePassword;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
            'password' => 'required|string|min:6',
        ]);


        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);


        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }


        $user = User::firstWhere('email', $passwordReset->email);


        $user->update($request->only('password'));


        $passwordReset->delete();

        return response(['message' =>'password has been successfully reset'], 200);
    }
}
