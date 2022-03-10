<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\PasswordReset;
use Validator;
use Illuminate\Support\Facades\Mail;


use Illuminate\Support\Facades\Hash;
use Session;
use Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Input;
use Response;
class PasswordResetController extends Controller {

    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function forget_password_view()
    {
        return view('users.forget_password');
    }
    public function create(Request $request) {
        $request->validate([
            'email' => 'required|exists:users',
        ]);

//        $validator = Validator::make($request->all(), [
//                    'email' => 'required|exists:users',
//        ]);
//        if ($validator->fails()) {
//            $errors = implode(', ', $validator->errors()->all());
//
//            return response()->json(array('message' => $errors), 400);
//        }
        $user = User::where('email', $request->email)->first();
        //dd($user);

        $passwordReset = PasswordReset::updateOrCreate(
                        ['email' => $user->email], [
                    'email' => $user->email,
                    'token' => str_random(60)
                        ]
        );
        $url = url('/password/find/' . $passwordReset->token);
        $datatosend['url'] = $url;
        $datatosend['name'] = $user->name;
        //dd($passwordReset);
        if ($user && $passwordReset)
            Mail::send('reset', $datatosend, function ($m) use ($user) {
                $m->from(env('MAIL_USERNAME'), 'Roadside App');
                $m->to($user->email, $user->name)->subject('Roadside Password Reset Email');
            });
        return back()->with('success','email send successfully');
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token) {
        $passwordReset = PasswordReset::where('token', $token)->first();
        if($passwordReset) {
            return view('reset_password_1');
        }
    }

    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
//    Not In Use
    /*
    public function reset(Request $request) {

        $request->validate([
            'password' => 'required|confirmed|min:6',
            'token' => 'required|string'
        ]);
        $passwordReset = PasswordReset::where('token', $request->token)->first();
        if (!$passwordReset)
            return view('reset_password_link_expired');
//            return sendError('This password reset token is invalid.', 404);
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return sendError('We cannot find a user with that e-mail address.', 404);
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        return sendSuccess('Password Successfully Changed!', $user);
    }
*/
}
