<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Cars;
use App\card;
use Illuminate\Support\Facades\Mail;
use File;
use App\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class LoginController extends Controller
{
    public function LoginUser()
    {
        $user = Auth::user();
        if(!isset($user))
        {
        return view('main/userlogin')->with(['title'=>'Login']);
        }else{
            return redirect('rescue')->with(['title'=>'dashboard']);
        }
    }

    public function varifieuser(Request $request){
         $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);

         if ($validator->fails()) {
             $response = array(
                 'error'=>1,
                 'message'=>'Invalid Username or Password',
             );
            return response()->json($response);
        }

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')]))
        {

            $userStripId = Auth::user()->strip_id;

             $response = array(
                 'error'=>0,
                 'message'=>'Login Successful.',
             );
            return response()->json($response);

        }else{
             $response = array(
                 'error'=>1,
                 'message'=>'Invalid Username or Password',
             );
            return response()->json($response);
        }

    }

    public function dashboard()
    {
        $user = Auth::user();
        if($user->id != '')
        {
        $cars =$user->myCars;
        return view('dashboard.dashboard')->with(['user'=>$user,'cars'=>$cars,'title'=>'User dashboard']);
        }else{
           return redirect('/help');
        }
    }

    public function getyears()
    {
        $optionsHtml = '<option disabled selected>Select Year</option>';
        $allYears = DB::table('all_cars')->groupBy('year')->orderBy('year', 'desc')->pluck('year');
        foreach($allYears as $year)
        {
            $optionsHtml .= '<option>'.$year.'</option>';
        }
        $response = array(
            'years'=>$optionsHtml,
        );
        return response()->json($response);
    }

    public function getmake(Request $request)
    {
         $optionsHtml = '<option disabled selected>Select Make</option>';
        $all_makes = DB::table('all_cars')->where('year', $request->year)->groupBy('make')->pluck('make');
        foreach($all_makes as $make)
        {
            $optionsHtml .= '<option>'.$make.'</option>';
        }
        $response = array(
            'makes'=>$optionsHtml,
        );
        return response()->json($response);
    }

    public function getmodal(Request $request)
    {
         $optionsHtml = '<option disabled selected>Select Model</option>';
        $all_models = DB::table('all_cars')->where('year', $request->year)->where('make', $request->make)->pluck('model');
        foreach($all_models as $modal)
        {
            $optionsHtml .= '<option>'.$modal.'</option>';
        }
        $response = array(
            'modals'=>$optionsHtml,
        );
        return response()->json($response);
    }


    public function carcreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|string',
            'color' => 'required|string',
//            'vin' => 'required|string',
        ]);
        if ($validator->fails()) {
            $response = array(
                 'error'=>1,
                 'message'=>'all fields ae required',
             );
            return response()->json($response);
        }
        $user = Auth::user();
        $input = $request->all();
        $input['user_id'] = $user->id;
        $input['name'] = $input['make'];
        $car = Cars::create($input);
          $response = array(
                 'error'=>0,
                 'message'=>'car added successfuly',
             );
            return response()->json($response);
    }

    //function for deleting user car..//

    public function deletecar(Request $request)
    {
        $result = Cars::where('id', $request->carId)->where('user_id', Auth::user()->id)->delete();
        if ($result) {
             $response = array(
                 'error'=>0,
                 'message'=>'car deleted successfully',
             );
            return response()->json($response);
        }

        $response = array(
                 'error'=>1,
                 'message'=>'Some thing went wrong',
             );
            return response()->json($response);
    }


    public function editprofile($id)
    {
         $user = User::where('id', $id)->first();
       return view('users.edituserProfile')->with(['user'=>$user,'title'=>'Edit Profile']);
    }

    public function updateProfileUser(Request $request)
    {
            $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string',
            'address' => 'required|string',
            'contact_number' => 'required',
//            'vin' => 'required|string',
        ]);
             if ($validator->fails()) {
            $response = array(
                 'error'=>1,
                 'message'=>'all fields ae required',
             );
            return response()->json($response);
        }
        $user = User::where('id', $request->userId)->first();
        $input = $request->all();
        if($user != Null)
        {
            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->address = $input['address'];
            $user->city = $input['city'];
            $user->state = $input['state'];
            $user->zipcode = $input['zipcode'];
            $user->country = $input['country'];
            $user->contact_number = $input['contact_number'];
            if($user->save())
            {
                $response = array(
                 'error'=>0,
                 'message'=>'Your profile is updated',
             );
            return response()->json($response);
            }else{
                $response = array(
                 'error'=>1,
                 'message'=>'something went wrong',
             );
            return response()->json($response);
            }
        }
    }

    public function logout()
    {
        Auth::logout();
         return redirect('/help');

    }

    public function changepassword()
    {
        return view('users.changepassword')->with(['user'=>Auth::user(),'title'=>'Change Password']);
    }



    public function checkPassword(Request $request)
    {
        $user = Auth::user();
        if (Hash::check($request->pass, $user->password))
        {
             $response = array(
                 'error'=>0,
//                 'message'=>'something went wrong',
             );
            return response()->json($response);
        }else{
             $response = array(
                 'error'=>1,
                 'message'=>'please use correct password',
             );
            return response()->json($response);
        }
    }

    public function updatepassword(Request $request)
    {
        if( User::find(auth()->user()->id)->update(['password'=> Hash::make($request->pass)]))
        {
             $response = array(
                 'error'=>0,
                 'message'=>'Password changed successfully',
             );
            return response()->json($response);
        }else{
             $response = array(
                 'error'=>1,
                 'message'=>'something went wrong',
             );
            return response()->json($response);
        }
    }


    public function updateProfielPic(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $path = public_path() . '/svg/';
        File::delete($path . $user->avatar);
        $image = $request->file('file');
        $filename = str_random(10) . '.' . $image->getClientOriginalExtension();
        $image->move($path, $filename);
        $user->avatar = $filename;
        $user->save();
          $response = array(
                 'error'=>0,
                 'message'=>'profile Image updated',
                 'imageName'=>$filename,
             );
            return response()->json($response);
    }

    public function paymentInfo()
    {
        $title = 'Payment Information';
        $user = Auth::user();
        $cards = $user->cards;
        return view('users.userPayments')->with(['title'=>$title,'user'=>$user,'cards'=>$cards]);
    }

    // function for reRest Password
    public function reSetLink() {
        $title = "Forget Password";
        return view('main.resetPassword')->with('title');
    }
    //

    public function createLink(Request $req)
    {

         $user = User::where('email', $req->email)->first();
         if($user){
        $passwordReset = PasswordReset::updateOrCreate(
             ['email' => $user->email], [
           'email' => $user->email,
           'token' => str_random(60)
               ]
        );
        $url = url('/change/' . $passwordReset->token);
        $datatosend['url'] = $url;
        $datatosend['name'] = $user->name;
       if ($user && $passwordReset)
           try{
            Mail::send('reset', $datatosend, function ($m) use ($user) {
                $m->from('info@www.driveroadside.com', 'Roadside App');
                $m->to($user->email, $user->name)->subject('Roadside Password Reset Email');
            });
           $response = ['error'=>0,'message'=>"Reset password Email has been sent"];
           return response()->json($response);
            }catch (\Exception $e){
            $response = ['error'=>1,'message'=>"Email Not Sent to your Address"];
           return response()->json($response);
        }
         }else{
             $response = ['error'=>1,'message'=>"Email Not Found"];
           return response()->json($response);
         }
    }

    public function change($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
        if($passwordReset) {
            $title = "Reset Password";
            return view('main.confrimNewPass')->with('title');
        }

    }

         function resetPass(Request $request) {
        $passwordReset = PasswordReset::where('token', $request->token)->first();
        if (!$passwordReset)
            return view('reset_password_link_expired');
//            return sendError('This password reset token is invalid.', 404);
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return view('reset_password_link_expired');
//            return sendError('We cannot find a user with that e-mail address.', 404);
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $response = ['error'=>0,'message'=>'YOur Password has been changed'];
//        return sendSuccess('Password Successfully Changed!', $user);
//        return redirect('/userlogin')->with('success','password Changed Successfully');
    }
}



