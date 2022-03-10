<?php

namespace App\Http\Controllers;

use App\ContactDetail;
use App\coupon;
use App\Service;
use App\Subscription;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Payment;
use App\cancelationReasons;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Session;
use Redirect;
use Response;
use Carbon\Carbon;

class UserController extends Controller {



    public function postLogin(Request $request) {

        $this->validate($request, ['email' => 'required', 'password' => 'required']);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('userdashboard');
        } else {
            return redirect()->back()->with('error', 'Invalid Email or Password');
        }
    }


    public function userDashboard() {
        $data['title'] = 'User Dashboard';
        $data['tab'] = 'User ';

        return view('users.user_dashboard', $data);
    }

    public function logout() {
        if (Auth::logout()) {
            return redirect('/user_login');
        } else {
            return redirect()->back()->with('error', 'Not Logout');
        }
    }

    public function getSubscription() {
        $user = Auth::user();
        if(isset($user->linked_id)){
            $id = $user->linked_id;
        }else{
            $id = $user->id;
        }
        $data['tab'] = 'subscription';
        $data['subscription'] = User::where('id', $id)->with('getSubscription', 'getPaymnet')->first();
//        echo "<pre>";
//        var_dump($data);exit;
        return view('users.subscription', $data);

    }

    public function cancelSubscription() {


        $id = Auth::id();
        $user = User::where('id', $id)->with('getSubscription')->first();


//        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
//        $sub = \Stripe\Subscription::retrieve($user->getSubscription->stripe_id);
//        $sub->cancel();

        $subscription = Subscription::where('user_id', $id)->first();
        if($subscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')){
//            User::where('linked_id', $user->id)->update(['linked_id' => null]);
        }
//        $subscription->delete();
        $user->subscription('RoadSide')->cancelNow();

        $subscription->is_cancelled =1;
        $subscription->save();

        $data['tab'] = 'subscription';
        $data['subscription'] = User::where('id', $id)->with('getSubscription', 'getPaymnet')->first();
        return redirect('usersubscription');
    }

    public function user_family_members(){
        $id = Auth::id();
        $data['tab'] = 'user_family_members';
        $data['family_members'] = User::where('linked_id', $id)->get();
        return view('users.user_family_members', $data);
    }

    function register_new_member(Request $request){
        if(User::where('linked_id', Auth::user()->getAuthIdentifier())->count() >= 3){
            return redirect()->back()->with('error', 'You cannot add no more then 3 members.');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'contact_number' => 'required',
            'address' => 'required',
            'zipcode' => 'required',
            'state' => 'required',
            'country' => 'required',
            'city' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = implode(', ', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $plain_password = $request->password;
        $token = bcrypt(str_random(15));

        $request->merge(['linked_id' => Auth::user()->getAuthIdentifier()]);
        $request->merge(['password' => bcrypt($request->password)]);
        $request->merge(['remember_token' => $token]);

        $user = User::create($request->all());
        $user->session_token = $user->createToken('RoadSide')->accessToken;
        $user->save();
//        $plan = User::where('id',$user->id)->getSubscription();
        $datatosend['planShow'] = 0;
        $datatosend['email'] = $user->email;
        $datatosend['password'] = $plain_password;

        try {
            Mail::send('emails.welcome_email', $datatosend, function ($m) use ($user) {
                $m->from('email@driveroadside.com', 'DRIVE | Roadside App');
                $m->to($user->email, $user->name)->subject('Welcome to DRIVE | ROADSIDE');
            });
        }catch (\Exception $e){
            return $this->sendError($e->getMessage());
        }

        return redirect()->back()->with('success', 'Member registered successfully.');
    }

    function remove_member($id){
        Service::where('used_by', $id)->update(['used_by' => null]);
        User::where('id', $id)->where('linked_id', Auth::user()->getAuthIdentifier())->delete();
        return redirect()->back()->with('success', 'Member removed successfully.');
    }

    public function addSubscriptionView() {
        $id = Auth::id();
        $data['tab'] = 'add_subscription_view';
        $data['subscription'] = User::where('id', $id)->with('getSubscription', 'getPaymnet')->first();

        return view('users.subscription_view', $data);
    }

    public function createSubscription($plan) {
        $id = Auth::id();
        $user = User::find($id);
//        dd($id);
        $data['tab'] = 'subscription';

        if ($plan == '10MilesMonthlyPlan') {
            $planToken = env('STRIPE_PLAN_MONTHLY_PLAN');
            $miles=10;
            $counter=4;

        } else if ($plan == '10MilesPlusYearFamilyPlan') {
            $planToken = env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN');
            $miles=10;
            $counter=4;

        } else if ($plan == '10MilesPlusYear') {
            $planToken = env('STRIPE_PLAN_1_YEAR_PLUS');
            $miles=10;
            $counter=4;

        } else if ($plan == '10MilesYear') {
            $planToken = env('STRIPE_PLAN_1_YEAR');
            $miles=10;
            $counter=2;

        } else if ($plan == '6Months') {
            $planToken = env('STRIPE_PLAN_6_MONTHS');
            $miles=10;
            $counter=1;
        }

        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

//       $token= \Stripe\Subscription::create();
            if ($get = \Stripe\Subscription::create([
                        "customer" => $user->stripe_id,
                        "items" => [
                            [
                                "plan" => $planToken,
                            ],
                        ]
                    ])) {

//                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
                if($get->plan->amount == null){
                    $get->plan->amount = 999;
                }
                $data['amount'] = $plan_amount = $get->plan->amount;
                $payement = new Payment();
                $payement->user_id = $user->id;
                $payement->amount = $plan_amount;
                $payement->charge_id = $get->id;
                $payement->save();
                $subscription = new Subscription;
                $subscription->name = 'RoadSide';
                $subscription->user_id = $id;
                $subscription->stripe_id = $get->id;
                $subscription->stripe_plan = $get->plan->id;
                $subscription->total_miles = $miles;
                $subscription->counter = $counter;
                $subscription->status = 1;
                $timestamp = $get->current_period_end;
                $subscription->ends_at = date('Y-m-d H:i:s', $timestamp);
//                $subscription->trial_ends_at =date('Y-m-d H﻿﻿', $timestamp);
//                $subscription->ends_at =date('Y-m-d H﻿﻿', $timestamp);
                $subscription->quantity = $get->quantity;
                $subscription->save();
                $data['stripe_detail'] = $get;
                $data['success'] = true;
                $data['user'] = $user;


//                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
            }
        } catch (\Stripe\Error\Card $e) {
//                Session::flash('error', $e->getMessage());
//                return redirect()->back()->withInput();

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\RateLimit $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\InvalidRequest $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\Base $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
        if(isset($user->linked_id)){
            $user->linked_id = null;
            $user->save();
        }
        return redirect('usersubscription');
    }

    public function upgradeSubscription($plan) {
        $id = Auth::id();
        $user = User::where('id', $id)->with('getSubscription')->first();
        $subscription = Subscription::where('user_id', $id)->first();
        $counter_s=(int)$subscription->counter;

        if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_MONTHLY_PLAN')) {
            $oldcounter=4;
        } elseif ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')) {
            $oldcounter=4;
        }else if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_PLUS')) {
            $oldcounter=4;
        } else if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR')) {
            $oldcounter=2;
        } else if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_6_MONTHS')) {
            $oldcounter=1;
        }

        if ($plan == '10MilesMonthlyPlan') {
            $planToken = env('STRIPE_PLAN_MONTHLY_PLAN');
            $miles=10;
            $counter=4;

        } else if ($plan == '10MilesPlusYearFamilyPlan') {
            $planToken = env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN');
            $miles=10;
            $counter=4;

        } else if ($plan == '10MilesPlusYear') {
            $planToken = env('STRIPE_PLAN_1_YEAR_PLUS');
            $miles=10;
            $counter=4;

        } else if ($plan == '10MilesYear') {
            $planToken = env('STRIPE_PLAN_1_YEAR');
            $miles=10;
            $counter=2;

        } else if ($plan == '6Months') {
            $planToken = env('STRIPE_PLAN_6_MONTHS');
            $miles=10;
            $counter=1;
        }

        $amount_off = 0;

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $sub = \Stripe\Subscription::retrieve($user->getSubscription->stripe_id);
        if($user->getSubscription->counter > 0 && $user->getSubscription->counter != $oldcounter){
            if($sub->plan->amount == null){
                $sub_amount = 999;
            }else{
                $sub_amount = $sub->plan->amount;
            }
            $amount_off = (($sub_amount/100) / $oldcounter) * $user->getSubscription->counter; //(1 - ($oldcounter / $user->getSubscription->counter )) * ($sub->plan->amount/100);
        } else if($user->getSubscription->counter == 0) {
            $amount_off = 0;
        } else {
            if($sub->plan->amount == null){
                $sub_amount = 999;
            }else{
                $sub_amount = $sub->plan->amount;
            }
            $amount_off = $sub_amount/100;
        }

        $amount_off =  floor($amount_off);
       $old_sub_count = $user->getSubscription->counter ;
        if($user->getSubscription->counter != 0) {
            try {

                $coupon = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'id' => 'discount-upgrade',
                    'amount_off' => $amount_off*100,
                    'currency' => 'usd',
                ]);

            } catch (Exception $e) {
                $coupon = \Stripe\Coupon::retrieve('discount-upgrade');
                $coupon->delete();
                $coupon = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'id' => 'discount-upgrade',
                    'amount_off' => $amount_off*100,
                    'currency' => 'usd',
                ]);
            } catch (\Stripe\Error\InvalidRequest $e) {
                $coupon = \Stripe\Coupon::retrieve('discount-upgrade');
                $coupon->delete();
                $coupon = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'id' => 'discount-upgrade',
                    'amount_off' => $amount_off*100,
                    'currency' => 'usd',
                ]);
            }
        }

        if($sub->status !='canceled'){
            $sub->cancel();
        }


        Subscription::where('user_id', $id)->delete();
        $data['tab'] = 'subscription';
        try {
//       $token= \Stripe\Subscription::create();
            if($old_sub_count != 0){
            if ($get = \Stripe\Subscription::create([
                        "customer" => $user->stripe_id,
                        "items" => [
                            [
                                "plan" => $planToken,
                            ],
                        ],
                        "coupon" => 'discount-upgrade',

                    ])) {

//                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
                if($get->plan->amount == null){
                    $get->plan->amount = 999;
                }

                    $data['amount'] = $plan_amount = $get->plan->amount;
                    $payement = new Payment();
                    $payement->user_id = $user->id;
                    $payement->amount = $plan_amount;
                    $payement->charge_id = $get->id;
                    $payement->save();
                    $subscription = new Subscription;
                    $subscription->name = 'RoadSide';
                    $subscription->user_id = $id;
                    $subscription->stripe_id = $get->id;
                    $subscription->stripe_plan = $get->plan->id;
                    $subscription->quantity = $get->quantity;
                    $timestamp = $get->current_period_end;
    //                $subscription->trial_ends_at =date('Y-m-d H﻿﻿', $timestamp);
//                    $subscription->ends_at = date('Y-m-d H﻿﻿:i:s', $timestamp);
                    $subscription->ends_at =date('Y-m-d H:i:s', $timestamp);
                    $subscription->total_miles = $miles;
                    $subscription->counter = $counter;
                    $subscription->status = 1;
                    $subscription->save();
                    $data['stripe_detail'] = $get;
                    $data['success'] = true;
                    $data['user'] = $user;

    //                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
                }
            } else {
                if ($get = \Stripe\Subscription::create([
                        "customer" => $user->stripe_id,
                        "items" => [
                            [
                                "plan" => $planToken,
                            ],
                        ]

                    ])) {

//                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
                    if($get->plan->amount == null){
                        $get->plan->amount = 999;
                    }
                $data['amount'] = $plan_amount = $get->plan->amount;
                $payement = new Payment();
                $payement->user_id = $user->id;
                $payement->amount = $plan_amount;
                $payement->charge_id = $get->id;
                $payement->save();
                $subscription = new Subscription;
                $subscription->name = 'RoadSide';
                $subscription->user_id = $id;
                $subscription->stripe_id = $get->id;
                $subscription->stripe_plan = $get->plan->id;
                $subscription->quantity = $get->quantity;
                $timestamp = $get->current_period_end;
//                $subscription->trial_ends_at =date('Y-m-d H﻿﻿', $timestamp);
                $subscription->ends_at =date('Y-m-d H:i:s', $timestamp);
                $subscription->total_miles = $miles;
                $subscription->counter = $counter;
                $subscription->status = 1;
                $subscription->save();
                $data['stripe_detail'] = $get;
                $data['success'] = true;
                $data['user'] = $user;

//                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
            }
            }
        } catch (\Stripe\Error\Card $e) {
//                Session::flash('error', $e->getMessage());
//                return redirect()->back()->withInput();

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\RateLimit $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\InvalidRequest $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\Base $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
        if(isset($user->linked_id)){
            $user->linked_id = null;
            $user->save();
        }
        return redirect('usersubscription');

        Subscription::where('user_id',$id)->delete();

        $data['tab']='subscription';
        $data['subscription']=User::where('id',$id)->with('getSubscription','getPaymnet')->first();
        return redirect('usersubscription');
    }

    //    Edit User Profile
    function editUserProfile()
    {

        $data['tab'] = 'user_edit_profile';
        $data['title'] = 'Edit My Profile';
        $data['detail'] = User::where('id',Auth::user()->id)->first();
        return view('users.user_edit_profile_view', $data);
    }

    //    Save user Profile Data
    function updateUserProfile(Request $request)
    {
        $request->validate([
            'full_name' => 'required|min:1|max:191',
            'email' => 'required|email|max:191',
            'profile_img' => 'image|mimes:jpeg,jpg,bmp,png,gif',
        ]);
        $id = Auth::id();
        //Check Image Exist
        $img_check = $request->hasFile('profile_img');
        //Fetch Admin From DB
        $data['user'] = User::where('id', Auth::user()->id)->first();
        //Set values against fields
        $data['user']->name = $request->full_name;
        //verify user chanage email or not
        if($request->email != '')
        {
            if($data['user']->email  != $request->email)
            {
                $data['user']->email == $request->email;
            }
        }
        $data['user']->email = $request->email;
        $data['user']->address = $request->address;
        $data['user']->zipcode = $request->zipcode;
        $data['user']->country = $request->country;
        $data['user']->city = $request->city;
        $data['user']->state = $request->state;


        //If New Image Added than delete old image and insert new image to DB and folder
        if ($img_check){

            $path = public_path() . '/svg/';
            File::delete($path . $data['user']->avatar);
            $image = $request->file('profile_img');
            $filename = str_random(10) . '.' . $image->getClientOriginalExtension();
            $image->move($path, $filename);
            $data['user']->avatar = $filename;
        }
        $data['user']->save();
        Session::flash('success', 'Updated successfully');
        return Redirect::to(URL::previous());
    }
    //    Chnage user Password
    function changeUserPassword(Request $request){
        // Setup the validator
        $message =['password.required'=> 'The new password field is required.'];
        $rules = array('current_password' => 'required', 'password' => 'min:5|required|confirmed|max:191','password_confirmation' => 'required|max:191', );
        $validator = Validator::make(Input::all(), $rules,$message);
        // Validate the input and return correct response
        if ($validator->fails())
        {   $errors = $validator->getMessageBag()->toArray();
            return Response::json(array(
                'status' => false,
                'message' => $errors,
                'from' => 'validator'

            ), 400); // 400 being the HTTP code for an invalid request.
        }
        $password  = Auth::user()->password;
        if (Hash::check($request['current_password'], $password)) {

            $id = Auth::user()->id;
            $user = User::where('id',$id)->first();
            $user->password = bcrypt($request->password);
            $user->save();
//            Session::flash('success', 'Password Updated successfully');
            return Response::json(array('status' => true, 'message' => 'Password Updated successfully'), 200);

        } else {
            //Session::flash('error', 'Invalid Old Password');
            //return Redirect::to(URL::previous());
            return Response::json(array('success' => false, 'message' => 'Invalid Old Password', 'from' => 'invalid'), 400);

        }
    }

    //Services
    public function getServices()
    {
        $data['title']= 'User Dashboard';
        $data['tab']='user_services';
        $data['services']= Service::where('user_id', Auth::user()->id)->get();

        return view('users.show_services_to_user', $data);

    }
    //Payment methods
    public function get_payment_method()
    {
        $data['title']= 'User Dashboard';
        $data['tab']='get_payment_method';

        return view('users.show_payment_method_to_user', $data);

    }
    public function sendError($error_message, $code = 400) {
        return redirect()->back()->withErrors($error_message);
    }

    //Update Payment method
    public function update_payment_method(Request $request)
    {
        $user = Auth::user();
        try {
            $stripe = \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $token =  \Stripe\PaymentMethod::create([
                'type' => 'card',
                'card' => [
                    'number' => $request->cardnumber,
                    'exp_month' => $request->month,
                    'exp_year' => $request->year,
                    'cvc' => $request->cvc,
                ],
                'billing_details' => [
                    'email' => $user->email,
                    'name' =>  $user->name,
                    'phone' => $user->contact_number,
                    'address' =>[
                        "line1" => $user->address,
                        "postal_code" => $user->zipcode,
                        "state" => $user->state,
                        "city" => $user->city,
                        "country" => $user->country
                    ]
                ],
            ]);
            $pm = \Stripe\PaymentMethod::retrieve(
                $token->id
            );
            $pm->attach(['customer' => $user->stripe_id]);

            $user->card_last_four = $token->card->last4;
            $user->card_brand = $token->card->brand;
            $user->save();

            \Stripe\Customer::update(
                $user->stripe_id,
                [ 'invoice_settings' => [ 'default_payment_method' => $token->id ] ]
            );

            $pms = \Stripe\PaymentMethod::all([
                'customer' => $user->stripe_id,
                'type' => 'card',
            ]);
            foreach($pms->data as $one){
                if($one->id == $token->id){
                    continue;
                }else{
                    $pm = \Stripe\PaymentMethod::retrieve(
                        $one->id
                    );
                    $pm->detach();
                }
            };
            return redirect()->back()->with('success', 'Payment method updated.');

        } catch (\Stripe\Error\Card $e) {
            return $this->sendError($e->getMessage());
        } catch (\Stripe\Error\RateLimit $e) {
            return $this->sendError($e->getMessage());
        } catch (\Stripe\Error\InvalidRequest $e) {
            return $this->sendError($e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {
            return $this->sendError($e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            return $this->sendError($e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            return $this->sendError($e->getMessage());
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    public function newSubscriptionCoupn(Request $request) {

        $id = Auth::id();
        $user = User::where('id', $id)->first();

        if ($request->plan == '10MilesMonthlyPlan') {
            $planToken = env('STRIPE_PLAN_MONTHLY_PLAN');
            $miles=10;
            $counter=4;

        } else if ($request->plan == '10MilesPlusYearFamilyPlan') {
            $planToken = env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN');
            $miles=10;
            $counter=4;
        }else if ($request->plan == '10MilesPlusYear') {
            $planToken = env('STRIPE_PLAN_1_YEAR_PLUS');
            $miles=10;
            $counter=4;
        } else if ($request->plan == '10MilesYear') {
            $planToken = env('STRIPE_PLAN_1_YEAR');
            $miles=10;
            $counter=2;
        } else if ($request->plan == '6Months') {
            $planToken = env('STRIPE_PLAN_6_MONTHS');
            $miles=10;
            $counter=1;
        }
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $token=  \Stripe\Token::create([
            'card' => [
                'number' => $request->number,
                'exp_month' => $request->date,
                'exp_year' => $request->year,
                'cvc' => $request->cvc
            ]
        ]);
        if($get = $user->newSubscription('RoadSide', $planToken)->create($token->id)){


            $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);

            $subscription = Subscription::where('stripe_id',$get->stripe_id)->first();
            $subscription->total_miles = $miles;
            $subscription->counter = $counter;
            $timestamp = $stripe_details->current_period_end;
            $subscription->ends_at = date('Y-m-d H:i:s', $timestamp);
            $subscription->save();

            if($stripe_details->plan->amount == null){
                $stripe_details->plan->amount = 999;
            }
            $data['amount'] = $plan_amount = $stripe_details->plan->amount;
            $payement = new Payment();
            $payement->user_id =  $user->id;
            $payement->amount = $plan_amount;
            $payement->charge_id = $get->stripe_id;
            $payement->save();


            $data['stripe_detail'] = $get;
            $data['success'] = true;
            $data['user'] = $user;
            dd($user);

//                return view('users.login', $data);
//                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
        }
//           } else {
//               User::where('id',$user->id)->delete();
//               return Response::json(array('success' => false, 'message' => 'Cant create Stripe Token'), 200);
//           }



//        return redirect('usersubscription');

//        Subscription::where('user_id',$id)->delete();
//
//        $data['tab']='subscription';
//        $data['subscription']=User::where('id',$id)->with('getSubscription','getPaymnet')->first();
        if(isset($user->linked_id)){
            $user->linked_id = null;
            $user->save();
        }
        return redirect('usersubscription');
    }


     public function newSubscription(Request $request) {



        $id = Auth::id();
        $user = User::where('id', $id)->first();
//         $subscription = Subscription::where('user_id', $id)->first();
//     $toatal_miles=(int)$subscription->total_miles;
//
//        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
//        $sub = \Stripe\Subscription::retrieve($user->getSubscription->stripe_id);
//        $sub->cancel();


//        Subscription::where('user_id', $id)->delete();
        $data['tab'] = 'subscription';
         if ($request->plan == '10MilesMonthlyPlan') {
             $planToken = env('STRIPE_PLAN_MONTHLY_PLAN');
             $miles=10;
             $counter=4;

         } else if ($request->plan == '10MilesPlusYearFamilyPlan') {
            $planToken = env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN');
              $miles=10;
              $counter=4;
        }else if ($request->plan == '10MilesPlusYear') {
            $planToken = env('STRIPE_PLAN_1_YEAR_PLUS');
              $miles=10;
              $counter=4;
        } else if ($request->plan == '10MilesYear') {
            $planToken = env('STRIPE_PLAN_1_YEAR');
              $miles=10;
              $counter=2;
        } else if ($request->plan == '6Months') {
            $planToken = env('STRIPE_PLAN_6_MONTHS');
              $miles=10;
              $counter=1;
        }
         \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $token=  \Stripe\Token::create([
                 'card' => [
                     'number' => $request->number,
                     'exp_month' => $request->date,
                     'exp_year' => $request->year,
                     'cvc' => $request->cvc
                 ]
             ]);
           if($get = $user->newSubscription('RoadSide', $planToken)->create($token->id)){


                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);

                $subscription = Subscription::where('stripe_id',$get->stripe_id)->first();
                $subscription->total_miles = $miles;
                $subscription->counter = $counter;
                $timestamp = $stripe_details->current_period_end;
                $subscription->ends_at = date('Y-m-d H:i:s', $timestamp);
                $subscription->save();

               if($stripe_details->plan->amount == null){
                   $stripe_details->plan->amount = 999;
               }
                $data['amount'] = $plan_amount = $stripe_details->plan->amount;
                $payement = new Payment();
                $payement->user_id =  $user->id;
                $payement->amount = $plan_amount;
                $payement->charge_id = $get->stripe_id;
                $payement->save();


                $data['stripe_detail'] = $get;
                $data['success'] = true;
                $data['user'] = $user;

//                return view('users.login', $data);
//                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
           }
//           } else {
//               User::where('id',$user->id)->delete();
//               return Response::json(array('success' => false, 'message' => 'Cant create Stripe Token'), 200);
//           }



//        return redirect('usersubscription');

//        Subscription::where('user_id',$id)->delete();
//
//        $data['tab']='subscription';
//        $data['subscription']=User::where('id',$id)->with('getSubscription','getPaymnet')->first();
         if(isset($user->linked_id)){
             $user->linked_id = null;
             $user->save();
         }
        return redirect('usersubscription');
    }

//    public function newSubscription2(Request $request) {
//
//
//
//        $id = Auth::id();
//        $user = User::where('id', $id)->first();
////         $subscription = Subscription::where('user_id', $id)->first();
////     $toatal_miles=(int)$subscription->total_miles;
////
////        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
////        $sub = \Stripe\Subscription::retrieve($user->getSubscription->stripe_id);
////        $sub->cancel();
//
//
////        Subscription::where('user_id', $id)->delete();
//        $data['tab'] = 'subscription';
//        if ($request->plan == '10MilesMonthlyPlan') {
//            $planToken = env('STRIPE_PLAN_MONTHLY_PLAN');
//            $miles=10;
//            $counter=4;
//
//        } else if ($request->plan == '10MilesPlusYearFamilyPlan') {
//            $planToken = env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN');
//            $miles=10;
//            $counter=4;
//        }else if ($request->plan == '10MilesPlusYear') {
//            $planToken = env('STRIPE_PLAN_1_YEAR_PLUS');
//            $miles=10;
//            $counter=4;
//        } else if ($request->plan == '10MilesYear') {
//            $planToken = env('STRIPE_PLAN_1_YEAR');
//            $miles=10;
//            $counter=2;
//        } else if ($request->plan == '6Months') {
//            $planToken = env('STRIPE_PLAN_6_MONTHS');
//            $miles=10;
//            $counter=1;
//        }
//        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
//        $token=  \Stripe\Token::create([
//            'card' => [
//                'number' => $request->number,
//                'exp_month' => $request->date,
//                'exp_year' => $request->year,
//                'cvc' => $request->cvc
//            ]
//        ]);
//        $user->newSubscription('RoadSide', $planToken)->create($token->id)
//        if(true){
//
//
//            $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
//
//            $subscription = Subscription::where('stripe_id',$get->stripe_id)->first();
//            $subscription->total_miles = $miles;
//            $subscription->counter = $counter;
//            $timestamp = $stripe_details->current_period_end;
//            $subscription->ends_at = date('Y-m-d H:i:s', $timestamp);
//            $subscription->save();
//
//            if($stripe_details->plan->amount == null){
//                $stripe_details->plan->amount = 999;
//            }
//            $data['amount'] = $plan_amount = $stripe_details->plan->amount;
//            $payement = new Payment();
//            $payement->user_id =  $user->id;
//            $payement->amount = $plan_amount;
//            $payement->charge_id = $get->stripe_id;
//            $payement->save();
//
//
//            $data['stripe_detail'] = $get;
//            $data['success'] = true;
//            $data['user'] = $user;
//
////                return view('users.login', $data);
////                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
//        }
////           } else {
////               User::where('id',$user->id)->delete();
////               return Response::json(array('success' => false, 'message' => 'Cant create Stripe Token'), 200);
////           }
//
//
//
////        return redirect('usersubscription');
//
////        Subscription::where('user_id',$id)->delete();
////
////        $data['tab']='subscription';
////        $data['subscription']=User::where('id',$id)->with('getSubscription','getPaymnet')->first();
//        if(isset($user->linked_id)){
//            $user->linked_id = null;
//            $user->save();
//        }
//        return redirect('usersubscription');
//    }
public function changeMembershipStatus()
{
    $subscriptions=Subscription::where('status',0)->get();
//     $subscriptions->status=5;
//     $subscriptions->save();
    $mytime = Carbon::now();
    foreach($subscriptions as $subscription)
    {

        $date=$subscription->created_at->addHours(48);

        if($mytime >= $date)
        {
          $subscription->status=1;
        }
         $subscription->save();
    }

}
    public function contact_us(Request $request){
        $datatosend['first_name'] = $request->first_name;
        $datatosend['last_name'] = $request->last_name;
        $datatosend['email'] = $request->email;
        $datatosend['phone_number'] = $request->phone_number;
        $datatosend['email_message'] = $request->message;
        try {

            Mail::send('emails.contact_us', $datatosend, function ($m) use ($request) {
                $m->from('email@driveroadside.com', 'Drive Roadside');
                $m->to( $request->email, $request->first_name.' '.$request->last_name)->subject('Welcome To RoadSide');
            });

//            ContactDetail::create($request->all());

            return redirect()->back()->with('success', "Your message is received, You  will be entertained very soon.");
       }catch (\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getUserSubscription()
    {
//        dd('hihaihsd');
        $user = Auth::user();
        if(isset($user->linked_id)){
            $id = $user->linked_id;
        }else{
            $id = $user->id;
        }
        $data['tab'] = 'subscription';
        $data['subscription'] = User::where('id', $id)->where('is_cancelled',0)->with('getSubscription', 'getPaymnet')->first();

        return view('users.subscription', $data);
    }

    public function createSubUser($planToken)
    {

        $id = Auth::id();
        $user = User::find($id);
        $planToken = $planToken;
        $sixMonts = env('STRIPE_PLAN_1_YEAR');
        $oneYear  = env('STRIPE_PLAN_1_YEAR_PLUS');
        if($sixMonts == $planToken)
        {
        $miles=10;
        $counter=2;
        }else{
        $miles=10;
        $counter=4;
        }

        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

//       $token= \Stripe\Subscription::create();
            if ($get = \Stripe\Subscription::create([
                        "customer" => $user->stripe_id,
                        "items" => [
                            [
                                "plan" => $planToken,
                            ],
                        ]
                    ])) {

//                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
                if($get->plan->amount == null){
                    $get->plan->amount = 999;
                }
                $data['amount'] = $plan_amount = $get->plan->amount;
                $payement = new Payment();
                $payement->user_id = $user->id;
                $payement->amount = $plan_amount;
                $payement->charge_id = $get->id;
                $payement->save();
                $subscription = new Subscription;
                $subscription->name = 'RoadSide';
                $subscription->user_id = $id;
                $subscription->stripe_id = $get->id;
                $subscription->stripe_plan = $get->plan->id;
                $subscription->total_miles = $miles;
                $subscription->counter = $counter;
                $subscription->status = 1;
                $timestamp = $get->current_period_end;
                $subscription->ends_at = date('Y-m-d H:i:s', $timestamp);
//                $subscription->trial_ends_at =date('Y-m-d H﻿﻿', $timestamp);
//                $subscription->ends_at =date('Y-m-d H﻿﻿', $timestamp);
                $subscription->quantity = $get->quantity;
                $subscription->save();
                $data['stripe_detail'] = $get;
                $data['success'] = true;
                $data['user'] = $user;


//                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
            }
        } catch (\Stripe\Error\Card $e) {
//                Session::flash('error', $e->getMessage());
//                return redirect()->back()->withInput();

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\RateLimit $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\InvalidRequest $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\Base $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
        if(isset($user->linked_id)){
            $user->linked_id = null;
            $user->save();
        }
        return redirect('subsecription');
    }

//    public function createSubUserCoupon(Request $re)
//    {
//
//        $id = Auth::id();
//        $user = User::find($id);
//        $planToken = $planToken;
//        $sixMonts = env('STRIPE_PLAN_1_YEAR');
//        $oneYear  = env('STRIPE_PLAN_1_YEAR_PLUS');
//        if($sixMonts == $planToken)
//        {
//            $miles=10;
//            $counter=2;
//        }else{
//            $miles=10;
//            $counter=4;
//        }
//
//        try {
//            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
//
////       $token= \Stripe\Subscription::create();
//            if ($get = \Stripe\Subscription::create([
//                "customer" => $user->stripe_id,
//                "items" => [
//                    [
//                        "plan" => $planToken,
//                    ],
//                ]
//            ])) {
//
////                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
//                if($get->plan->amount == null){
//                    $get->plan->amount = 999;
//                }
//                $data['amount'] = $plan_amount = $get->plan->amount;
//                $payement = new Payment();
//                $payement->user_id = $user->id;
//                $payement->amount = $plan_amount;
//                $payement->charge_id = $get->id;
//                $payement->save();
//                $subscription = new Subscription;
//                $subscription->name = 'RoadSide';
//                $subscription->user_id = $id;
//                $subscription->stripe_id = $get->id;
//                $subscription->stripe_plan = $get->plan->id;
//                $subscription->total_miles = $miles;
//                $subscription->counter = $counter;
//                $subscription->status = 1;
//                $timestamp = $get->current_period_end;
//                $subscription->ends_at = date('Y-m-d H:i:s', $timestamp);
////                $subscription->trial_ends_at =date('Y-m-d H﻿﻿', $timestamp);
////                $subscription->ends_at =date('Y-m-d H﻿﻿', $timestamp);
//                $subscription->quantity = $get->quantity;
//                $subscription->save();
//                $data['stripe_detail'] = $get;
//                $data['success'] = true;
//                $data['user'] = $user;
//
//
////                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
//            }
//        } catch (\Stripe\Error\Card $e) {
////                Session::flash('error', $e->getMessage());
////                return redirect()->back()->withInput();
//
//            return redirect()->back()->withInput()->with('error', $e->getMessage());
//        } catch (\Stripe\Error\RateLimit $e) {
//
//            return redirect()->back()->withInput()->with('error', $e->getMessage());
//        } catch (\Stripe\Error\InvalidRequest $e) {
//
//            return redirect()->back()->withInput()->with('error', $e->getMessage());
//        } catch (\Stripe\Error\Authentication $e) {
//
//            return redirect()->back()->withInput()->with('error', $e->getMessage());
//        } catch (\Stripe\Error\ApiConnection $e) {
//
//            return redirect()->back()->withInput()->with('error', $e->getMessage());
//        } catch (\Stripe\Error\Base $e) {
//
//            return redirect()->back()->withInput()->with('error', $e->getMessage());
//        } catch (Exception $e) {
//            return redirect()->back()->withInput()->with('error', $e->getMessage());
//        }
//        if(isset($user->linked_id)){
//            $user->linked_id = null;
//            $user->save();
//        }
//        return redirect('subsecription');
//    }
    public function upgradeSubscriptionUser($planToken)
    {
         $id = Auth::id();
        $user = User::where('id', $id)->with('getSubscription')->first();
        $subscription = Subscription::where('user_id', $id)->first();
        $counter_s=(int)$subscription->counter;
            $oldcounter = 1;
         if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR')) {
            $oldcounter=2;
        } else if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_6_MONTHS')) {
            $oldcounter=1;
        }

        $planToken = $planToken;
        $miles=10;
        $counter=4;

        $amount_off = 0;

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $sub = \Stripe\Subscription::retrieve($user->getSubscription->stripe_id);
        if($user->getSubscription->counter > 0 && $user->getSubscription->counter != $oldcounter){
            if($sub->plan->amount == null){
                $sub_amount = 999;
            }else{
                $sub_amount = $sub->plan->amount;
            }
            $amount_off = (($sub_amount/100) / $oldcounter) * $user->getSubscription->counter; //(1 - ($oldcounter / $user->getSubscription->counter )) * ($sub->plan->amount/100);
        } else if($user->getSubscription->counter == 0) {
            $amount_off = 0;
        } else {
            if($sub->plan->amount == null){
                $sub_amount = 999;
            }else{
                $sub_amount = $sub->plan->amount;
            }
            $amount_off = $sub_amount/100;
        }

        $amount_off =  floor($amount_off);
       $old_sub_count = $user->getSubscription->counter ;
        if($user->getSubscription->counter != 0) {
            try {

                $coupon = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'id' => 'discount-upgrade',
                    'amount_off' => $amount_off*100,
                    'currency' => 'usd',
                ]);

            } catch (Exception $e) {
                $coupon = \Stripe\Coupon::retrieve('discount-upgrade');
                $coupon->delete();
                $coupon = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'id' => 'discount-upgrade',
                    'amount_off' => $amount_off*100,
                    'currency' => 'usd',
                ]);
            } catch (\Stripe\Error\InvalidRequest $e) {
                $coupon = \Stripe\Coupon::retrieve('discount-upgrade');
                $coupon->delete();
                $coupon = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'id' => 'discount-upgrade',
                    'amount_off' => $amount_off*100,
                    'currency' => 'usd',
                ]);
            }
        }

        if($sub->status !='canceled'){
            $sub->cancel();
        }


        Subscription::where('user_id', $id)->delete();
        $data['tab'] = 'subscription';
        try {
//       $token= \Stripe\Subscription::create();
            if($old_sub_count != 0){
            if ($get = \Stripe\Subscription::create([
                        "customer" => $user->stripe_id,
                        "items" => [
                            [
                                "plan" => $planToken,
                            ],
                        ],
                        "coupon" => 'discount-upgrade',

                    ])) {

//                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
                if($get->plan->amount == null){
                    $get->plan->amount = 999;
                }

                    $data['amount'] = $plan_amount = $get->plan->amount;
                    $payement = new Payment();
                    $payement->user_id = $user->id;
                    $payement->amount = $plan_amount;
                    $payement->charge_id = $get->id;
                    $payement->save();
                    $subscription = new Subscription;
                    $subscription->name = 'RoadSide';
                    $subscription->user_id = $id;
                    $subscription->stripe_id = $get->id;
                    $subscription->stripe_plan = $get->plan->id;
                    $subscription->quantity = $get->quantity;
                    $timestamp = $get->current_period_end;
    //                $subscription->trial_ends_at =date('Y-m-d H﻿﻿', $timestamp);
//                    $subscription->ends_at = date('Y-m-d H﻿﻿:i:s', $timestamp);
                    $subscription->ends_at =date('Y-m-d H:i:s', $timestamp);
                    $subscription->total_miles = $miles;
                    $subscription->counter = $counter;
                    $subscription->status = 1;
                    $subscription->save();
                    $data['stripe_detail'] = $get;
                    $data['success'] = true;
                    $data['user'] = $user;

    //                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
                }
            } else {
                if ($get = \Stripe\Subscription::create([
                        "customer" => $user->stripe_id,
                        "items" => [
                            [
                                "plan" => $planToken,
                            ],
                        ]

                    ])) {

//                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
                    if($get->plan->amount == null){
                        $get->plan->amount = 999;
                    }
                $data['amount'] = $plan_amount = $get->plan->amount;
                $payement = new Payment();
                $payement->user_id = $user->id;
                $payement->amount = $plan_amount;
                $payement->charge_id = $get->id;
                $payement->save();
                $subscription = new Subscription;
                $subscription->name = 'RoadSide';
                $subscription->user_id = $id;
                $subscription->stripe_id = $get->id;
                $subscription->stripe_plan = $get->plan->id;
                $subscription->quantity = $get->quantity;
                $timestamp = $get->current_period_end;
//                $subscription->trial_ends_at =date('Y-m-d H﻿﻿', $timestamp);
                $subscription->ends_at =date('Y-m-d H:i:s', $timestamp);
                $subscription->total_miles = $miles;
                $subscription->counter = $counter;
                $subscription->status = 1;
                $subscription->save();
                $data['stripe_detail'] = $get;
                $data['success'] = true;
                $data['user'] = $user;

//                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
            }
            }
        } catch (\Stripe\Error\Card $e) {
//                Session::flash('error', $e->getMessage());
//                return redirect()->back()->withInput();

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\RateLimit $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\InvalidRequest $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\Base $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
        if(isset($user->linked_id)){
            $user->linked_id = null;
            $user->save();
        }
        return redirect('subsecription');

        Subscription::where('user_id',$id)->delete();

//        $data['tab']='subscription';
        $data['subscription']=User::where('id',$id)->with('getSubscription','getPaymnet')->first();
        return redirect('subsecription');
    }

    public function cancelSubscriptionUser(Request $req)
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();

        $reason = new cancelationReasons;
        $reason->user_id = Auth::id();
        $reason->reason  = $req->reason;
        $reason->detail_reason = $req->notes;
        $reason->cancel_type   = 'Subsecription';
        $reason->save();
//        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
//        $sub = \Stripe\Subscription::retrieve($user->getSubscription->stripe_id);
//        $sub->cancel();

        $subscription = Subscription::where('user_id', $id)->orderBy('id', 'DESC')->first();
//        dd($subscription);
//        if($subscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')){
////            User::where('linked_id', $user->id)->update(['linked_id' => null]);
//        }
//        $subscription->delete();
//        $user->subscription('RoadSide')->cancelNow();

        $subscription->is_cancelled = 1;
        $subscription->save();
//
//        $data['tab'] = 'subscription';
//        $data['subscription'] = User::where('id', $id)->with('getSubscription', 'getPaymnet')->first();
        return redirect('subsecription');
    }

    public function updateSubsecriptionfrom()
    {
        $id = Auth::id();
        $user = User::where('id', $id)->with('getSubscription')->first();
        $subscription = Subscription::where('user_id', $id)->first();
        $counter_s=(int)$subscription->counter;

        if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_MONTHLY_PLAN')) {
            $oldcounter=4;
        } elseif ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')) {
            $oldcounter=4;
        }else if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_PLUS')) {
            $oldcounter=4;
        } else if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR')) {
            $oldcounter=2;
        } else if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_6_MONTHS')) {
            $oldcounter=1;
        }

        $planToken = env('STRIPE_PLAN_1_YEAR');
        $miles=10;
        $counter=4;

        $amount_off = 0;

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $sub = \Stripe\Subscription::retrieve($user->getSubscription->stripe_id);
        if($user->getSubscription->counter > 0 && $user->getSubscription->counter != $oldcounter){
            if($sub->plan->amount == null){
                $sub_amount = 999;
            }else{
                $sub_amount = $sub->plan->amount;
            }
            $amount_off = (($sub_amount/100) / $oldcounter) * $user->getSubscription->counter; //(1 - ($oldcounter / $user->getSubscription->counter )) * ($sub->plan->amount/100);
        } else if($user->getSubscription->counter == 0) {
            $amount_off = 0;
        } else {
            if($sub->plan->amount == null){
                $sub_amount = 999;
            }else{
                $sub_amount = $sub->plan->amount;
            }
            $amount_off = $sub_amount/100;
        }

        $amount_off =  floor($amount_off);
        $old_sub_count = $user->getSubscription->counter ;
        if($user->getSubscription->counter != 0) {
            try {

                $coupon = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'id' => 'discount-upgrade',
                    'amount_off' => $amount_off*100,
                    'currency' => 'usd',
                ]);

            } catch (Exception $e) {
                $coupon = \Stripe\Coupon::retrieve('discount-upgrade');
                $coupon->delete();
                $coupon = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'id' => 'discount-upgrade',
                    'amount_off' => $amount_off*100,
                    'currency' => 'usd',
                ]);
            } catch (\Stripe\Error\InvalidRequest $e) {
                $coupon = \Stripe\Coupon::retrieve('discount-upgrade');
                $coupon->delete();
                $coupon = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'id' => 'discount-upgrade',
                    'amount_off' => $amount_off*100,
                    'currency' => 'usd',
                ]);
            }
        }

        if($sub->status !='canceled'){
            $sub->cancel();
        }


        Subscription::where('user_id', $id)->delete();
        $data['tab'] = 'subscription';
        try {
//       $token= \Stripe\Subscription::create();
            if($old_sub_count != 0){
                if ($get = \Stripe\Subscription::create([
                    "customer" => $user->stripe_id,
                    "items" => [
                        [
                            "plan" => $planToken,
                        ],
                    ],
                    "coupon" => 'discount-upgrade',

                ])) {

//                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
                    if($get->plan->amount == null){
                        $get->plan->amount = 999;
                    }

                    $data['amount'] = $plan_amount = $get->plan->amount;
                    $payement = new Payment();
                    $payement->user_id = $user->id;
                    $payement->amount = $plan_amount;
                    $payement->charge_id = $get->id;
                    $payement->save();
                    $subscription = new Subscription;
                    $subscription->name = 'RoadSide';
                    $subscription->user_id = $id;
                    $subscription->stripe_id = $get->id;
                    $subscription->stripe_plan = $get->plan->id;
                    $subscription->quantity = $get->quantity;
                    $timestamp = $get->current_period_end;
                    //                $subscription->trial_ends_at =date('Y-m-d H﻿﻿', $timestamp);
//                    $subscription->ends_at = date('Y-m-d H﻿﻿:i:s', $timestamp);
                    $subscription->ends_at =date('Y-m-d H:i:s', $timestamp);
                    $subscription->total_miles = $miles;
                    $subscription->counter = $counter;
                    $subscription->status = 1;
                    $subscription->save();
                    $data['stripe_detail'] = $get;
                    $data['success'] = true;
                    $data['user'] = $user;

                    //                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
                }
            } else {
                if ($get = \Stripe\Subscription::create([
                    "customer" => $user->stripe_id,
                    "items" => [
                        [
                            "plan" => $planToken,
                        ],
                    ]

                ])) {

//                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
                    if($get->plan->amount == null){
                        $get->plan->amount = 999;
                    }
                    $data['amount'] = $plan_amount = $get->plan->amount;
                    $payement = new Payment();
                    $payement->user_id = $user->id;
                    $payement->amount = $plan_amount;
                    $payement->charge_id = $get->id;
                    $payement->save();
                    $subscription = new Subscription;
                    $subscription->name = 'RoadSide';
                    $subscription->user_id = $id;
                    $subscription->stripe_id = $get->id;
                    $subscription->stripe_plan = $get->plan->id;
                    $subscription->quantity = $get->quantity;
                    $timestamp = $get->current_period_end;
//                $subscription->trial_ends_at =date('Y-m-d H﻿﻿', $timestamp);
                    $subscription->ends_at =date('Y-m-d H:i:s', $timestamp);
                    $subscription->total_miles = $miles;
                    $subscription->counter = $counter;
                    $subscription->status = 1;
                    $subscription->save();
                    $data['stripe_detail'] = $get;
                    $data['success'] = true;
                    $data['user'] = $user;

//                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
                }
            }
        } catch (\Stripe\Error\Card $e) {
//                Session::flash('error', $e->getMessage());
//                return redirect()->back()->withInput();

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\RateLimit $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\InvalidRequest $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\Base $e) {

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
        if(isset($user->linked_id)){
            $user->linked_id = null;
            $user->save();
        }
        return redirect('subsecription');

        Subscription::where('user_id',$id)->delete();

//        $data['tab']='subscription';
        $data['subscription']=User::where('id',$id)->with('getSubscription','getPaymnet')->first();
        return redirect('subsecription');
    }
///
    public function updateSubsecriptionfromcoupon(Request $re)
    {
        $coupon = $re->coupon;
        $coupon = coupon::where('coupon',$coupon)->first();
        if (!isset($coupon))
        {
            $response = array(
                'error'=>1,
                'message'=>'Invalid Coupon',
            );
            return response()->json($response);
        }
        $expirey = $coupon->valid;
        $current = strtotime(date("Y-m-d H:i:s"));
        if($expirey < $current)
        {
            $response = array(
                'error'=>1,
                'message'=>'Expire Coupon',
            );
            return response()->json($response);
        }
        if($coupon == Null)
        {
            $response = array(
                'error'=>1,
                'message'=>'Invalid Coupon',
            );
            return response()->json($response);
        }
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $coupon =  \Stripe\Coupon::retrieve($re->coupon,[]);

        }catch (\Stripe\Error\RateLimit $e)
        {
            $response = array(
                'error'=>1,
                'message'=>'Invalid Coupon',
            );
            return response()->json($response);
        }catch (\Stripe\Error\InvalidRequest $e)
        {
            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        }
        $id = Auth::id();
        $user = User::where('id', $id)->with('getSubscription')->first();
        $subscription = Subscription::where('user_id', $id)->first();
        $counter_s=(int)$subscription->counter;

        if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_MONTHLY_PLAN')) {
            $oldcounter=4;
        } elseif ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')) {
            $oldcounter=4;
        }else if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_PLUS')) {
            $oldcounter=4;
        } else if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR')) {
            $oldcounter=2;
        } else if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_6_MONTHS')) {
            $oldcounter=1;
        }

        $planToken = $re->plan_id;
        $miles=10;
        $counter=4;

        $amount_off = 0;

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $sub = \Stripe\Subscription::retrieve($user->getSubscription->stripe_id);
        if($user->getSubscription->counter > 0 && $user->getSubscription->counter != $oldcounter){
            if($sub->plan->amount == null){
                $sub_amount = 999;
            }else{
                $sub_amount = $sub->plan->amount;
            }
            $amount_off = (($sub_amount/100) / $oldcounter) * $user->getSubscription->counter; //(1 - ($oldcounter / $user->getSubscription->counter )) * ($sub->plan->amount/100);
        } else if($user->getSubscription->counter == 0) {

            $amount_off = 0;
        } else {
            if($sub->plan->amount == null){
                $sub_amount = 999;
            }else{
                $sub_amount = $sub->plan->amount;
            }
            $amount_off = $sub_amount/100;
        }
        $Discount  = $coupon->percent_off/100;
        $amount_off =  floor($Discount*$amount_off);
        $old_sub_count = $user->getSubscription->counter ;
        if($user->getSubscription->counter != 0) {
            try {

                $coupon = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'id' => 'discount-upgrade',
                    'amount_off' => $amount_off*100,
                    'currency' => 'usd',
                ]);

            } catch (Exception $e) {
                $coupon = \Stripe\Coupon::retrieve('discount-upgrade');
                $coupon->delete();
                $coupon = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'id' => 'discount-upgrade',
                    'amount_off' => $amount_off*100,
                    'currency' => 'usd',
                ]);
            } catch (\Stripe\Error\InvalidRequest $e) {
                $coupon = \Stripe\Coupon::retrieve('discount-upgrade');
                $coupon->delete();
                $coupon = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'id' => 'discount-upgrade',
                    'amount_off' => $amount_off*100,
                    'currency' => 'usd',
                ]);
            }
        }

        if($sub->status !='canceled'){
            $sub->cancel();
        }


        Subscription::where('user_id', $id)->delete();
        $data['tab'] = 'subscription';
        try {
//       $token= \Stripe\Subscription::create();
            if($old_sub_count != 0){
                if ($get = \Stripe\Subscription::create([
                    "customer" => $user->stripe_id,
                    "items" => [
                        [
                            "plan" => $planToken,
                        ],
                    ],
                    "coupon" => 'discount-upgrade',

                ])) {

//                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
                    if($get->plan->amount == null){
                        $get->plan->amount = 999;
                    }

                    $data['amount'] = $plan_amount = $get->plan->amount;
                    $payement = new Payment();
                    $payement->user_id = $user->id;
                    $payement->amount = $plan_amount;
                    $payement->charge_id = $get->id;
                    $payement->save();
                    $subscription = new Subscription;
                    $subscription->name = 'RoadSide';
                    $subscription->user_id = $id;
                    $subscription->stripe_id = $get->id;
                    $subscription->stripe_plan = $get->plan->id;
                    $subscription->quantity = $get->quantity;
                    $timestamp = $get->current_period_end;
                    //                $subscription->trial_ends_at =date('Y-m-d H﻿﻿', $timestamp);
//                    $subscription->ends_at = date('Y-m-d H﻿﻿:i:s', $timestamp);
                    $subscription->ends_at =date('Y-m-d H:i:s', $timestamp);
                    $subscription->total_miles = $miles;
                    $subscription->counter = $counter;
                    $subscription->status = 1;
                    $subscription->save();
                    $data['stripe_detail'] = $get;
                    $data['success'] = true;
                    $data['user'] = $user;

                    //                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
                }
            } else {
                if ($get = \Stripe\Subscription::create([
                    "customer" => $user->stripe_id,
                    "items" => [
                        [
                            "plan" => $planToken,
                        ],
                        'coupon'=>$coupon-$id
                    ]

                ])) {

//                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
                    if($get->plan->amount == null){
                        $get->plan->amount = 999;
                    }
                    $data['amount'] = $plan_amount = $get->plan->amount;
                    $payement = new Payment();
                    $payement->user_id = $user->id;
                    $payement->amount = $plan_amount;
                    $payement->charge_id = $get->id;
                    $payement->save();
                    $subscription = new Subscription;
                    $subscription->name = 'RoadSide';
                    $subscription->user_id = $id;
                    $subscription->stripe_id = $get->id;
                    $subscription->stripe_plan = $get->plan->id;
                    $subscription->quantity = $get->quantity;
                    $timestamp = $get->current_period_end;
//                $subscription->trial_ends_at =date('Y-m-d H﻿﻿', $timestamp);
                    $subscription->ends_at =date('Y-m-d H:i:s', $timestamp);
                    $subscription->total_miles = $miles;
                    $subscription->counter = $counter;
                    $subscription->status = 1;
                    $subscription->save();
                    $data['stripe_detail'] = $get;
                    $data['success'] = true;
                    $data['user'] = $user;

//                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
                }
            }
        } catch (\Stripe\Error\Card $e) {
//                Session::flash('error', $e->getMessage());
//                return redirect()->back()->withInput();

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\RateLimit $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\InvalidRequest $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\Authentication $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\ApiConnection $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\Base $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (Exception $e) {
            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        }
        if(isset($user->linked_id)){
            $user->linked_id = null;
            $user->save();
        }
        $response = array(
            'error'=>0,
            'message'=>'$e->getMessage()',
        );
        return response()->json($response);

        Subscription::where('user_id',$id)->delete();

//        $data['tab']='subscription';
        $data['subscription']=User::where('id',$id)->with('getSubscription','getPaymnet')->first();
        return redirect('subsecription');
    }
    public function updateSubsecriptionfromcoupon1(Request $re)
    {
        $coupon = $re->coupon;
        $coupon = coupon::where('coupon',$coupon)->first();
        if (!isset($coupon))
        {
            $response = array(
                'error'=>1,
                'message'=>'Invalid Coupon',
            );
            return response()->json($response);
        }
        $expirey = $coupon->valid;
        $current = strtotime(date("Y-m-d H:i:s"));
        if($expirey < $current)
        {
            $response = array(
                'error'=>1,
                'message'=>'Expire Coupon',
            );
            return response()->json($response);
        }
        if($coupon == Null)
        {
            $response = array(
                'error'=>1,
                'message'=>'Invalid Coupon',
            );
            return response()->json($response);
        }
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $coupon =  \Stripe\Coupon::retrieve($re->coupon,[]);

        }catch (\Stripe\Error\RateLimit $e)
        {
            $response = array(
                'error'=>1,
                'message'=>'Invalid Coupon',
            );
            return response()->json($response);
        }catch (\Stripe\Error\InvalidRequest $e)
        {
            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        }
        $id = Auth::id();
        $user = User::where('id', $id)->with('getSubscription')->first();
        $subscription = Subscription::where('user_id', $id)->first();
        $counter_s=(int)$subscription->counter;

        if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_MONTHLY_PLAN')) {
            $oldcounter=4;
        } elseif ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')) {
            $oldcounter=4;
        }else if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_PLUS')) {
            $oldcounter=4;
        } else if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR')) {
            $oldcounter=2;
        } else if ($user->getSubscription->stripe_plan == env('STRIPE_PLAN_6_MONTHS')) {
            $oldcounter=1;
        }

        $planToken = env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN');
        $miles=10;
        $counter=4;

        $amount_off = 0;

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $sub = \Stripe\Subscription::retrieve($user->getSubscription->stripe_id);
        if($user->getSubscription->counter > 0 && $user->getSubscription->counter != $oldcounter){
            if($sub->plan->amount == null){
                $sub_amount = 999;
            }else{
                $sub_amount = $sub->plan->amount;
            }
            $amount_off = (($sub_amount/100) / $oldcounter) * $user->getSubscription->counter; //(1 - ($oldcounter / $user->getSubscription->counter )) * ($sub->plan->amount/100);
        } else if($user->getSubscription->counter == 0) {

            $amount_off = 0;
        } else {
            if($sub->plan->amount == null){
                $sub_amount = 999;
            }else{
                $sub_amount = $sub->plan->amount;
            }
            $amount_off = $sub_amount/100;
        }
        $Discount  = $coupon->percent_off/100;
        $amount_off =  floor($Discount*$amount_off);
        $old_sub_count = $user->getSubscription->counter ;
        if($user->getSubscription->counter != 0) {
            try {

                $coupon = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'id' => 'discount-upgrade',
                    'amount_off' => $amount_off*100,
                    'currency' => 'usd',
                ]);

            } catch (Exception $e) {
                $coupon = \Stripe\Coupon::retrieve('discount-upgrade');
                $coupon->delete();
                $coupon = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'id' => 'discount-upgrade',
                    'amount_off' => $amount_off*100,
                    'currency' => 'usd',
                ]);
            } catch (\Stripe\Error\InvalidRequest $e) {
                $coupon = \Stripe\Coupon::retrieve('discount-upgrade');
                $coupon->delete();
                $coupon = \Stripe\Coupon::create([
                    'duration' => 'once',
                    'id' => 'discount-upgrade',
                    'amount_off' => $amount_off*100,
                    'currency' => 'usd',
                ]);
            }
        }

        if($sub->status !='canceled'){
            $sub->cancel();
        }


        Subscription::where('user_id', $id)->delete();
        $data['tab'] = 'subscription';
        try {
//       $token= \Stripe\Subscription::create();
            if($old_sub_count != 0){
                if ($get = \Stripe\Subscription::create([
                    "customer" => $user->stripe_id,
                    "items" => [
                        [
                            "plan" => $planToken,
                        ],
                    ],
                    "coupon" => 'discount-upgrade',

                ])) {

//                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
                    if($get->plan->amount == null){
                        $get->plan->amount = 999;
                    }

                    $data['amount'] = $plan_amount = $get->plan->amount;
                    $payement = new Payment();
                    $payement->user_id = $user->id;
                    $payement->amount = $plan_amount;
                    $payement->charge_id = $get->id;
                    $payement->save();
                    $subscription = new Subscription;
                    $subscription->name = 'RoadSide';
                    $subscription->user_id = $id;
                    $subscription->stripe_id = $get->id;
                    $subscription->stripe_plan = $get->plan->id;
                    $subscription->quantity = $get->quantity;
                    $timestamp = $get->current_period_end;
                    //                $subscription->trial_ends_at =date('Y-m-d H﻿﻿', $timestamp);
//                    $subscription->ends_at = date('Y-m-d H﻿﻿:i:s', $timestamp);
                    $subscription->ends_at =date('Y-m-d H:i:s', $timestamp);
                    $subscription->total_miles = $miles;
                    $subscription->counter = $counter;
                    $subscription->status = 1;
                    $subscription->save();
                    $data['stripe_detail'] = $get;
                    $data['success'] = true;
                    $data['user'] = $user;

                    //                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
                }
            } else {
                if ($get = \Stripe\Subscription::create([
                    "customer" => $user->stripe_id,
                    "items" => [
                        [
                            "plan" => $planToken,
                        ],
                        'coupon'=>$coupon-$id
                    ]

                ])) {

//                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
                    if($get->plan->amount == null){
                        $get->plan->amount = 999;
                    }
                    $data['amount'] = $plan_amount = $get->plan->amount;
                    $payement = new Payment();
                    $payement->user_id = $user->id;
                    $payement->amount = $plan_amount;
                    $payement->charge_id = $get->id;
                    $payement->save();
                    $subscription = new Subscription;
                    $subscription->name = 'RoadSide';
                    $subscription->user_id = $id;
                    $subscription->stripe_id = $get->id;
                    $subscription->stripe_plan = $get->plan->id;
                    $subscription->quantity = $get->quantity;
                    $timestamp = $get->current_period_end;
//                $subscription->trial_ends_at =date('Y-m-d H﻿﻿', $timestamp);
                    $subscription->ends_at =date('Y-m-d H:i:s', $timestamp);
                    $subscription->total_miles = $miles;
                    $subscription->counter = $counter;
                    $subscription->status = 1;
                    $subscription->save();
                    $data['stripe_detail'] = $get;
                    $data['success'] = true;
                    $data['user'] = $user;

//                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
                }
            }
        } catch (\Stripe\Error\Card $e) {
//                Session::flash('error', $e->getMessage());
//                return redirect()->back()->withInput();

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\RateLimit $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\InvalidRequest $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\Authentication $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\ApiConnection $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\Base $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (Exception $e) {
            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        }
        if(isset($user->linked_id)){
            $user->linked_id = null;
            $user->save();
        }
        $response = array(
            'error'=>0,
            'message'=>'$e->getMessage()',
        );


        Subscription::where('user_id',$id)->delete();

//        $data['tab']='subscription';
        $data['subscription']=User::where('id',$id)->with('getSubscription','getPaymnet')->first();
        return response()->json($response);
        return redirect('subsecription');
    }

    public function createSubUserwithcoupon(Request $re)
    {
        $coupon = $re->coupon;

        if($coupon == Null)
        {
            $response = array(
                'error'=>1,
                'message'=>'Invalid Coupon',
            );
            return response()->json($response);
        }
        $coupon = coupon::where('coupon',$coupon)->first();
        if(!isset($coupon->valid))
        {
            $response = array(
                'error'=>1,
                'message'=>'Invalid Coupon',
            );
            return response()->json($response);
        }
        $expirey = $coupon->valid;
        $current = strtotime(date("Y-m-d H:i:s"));
        if($expirey < $current)
        {
            $response = array(
                'error'=>1,
                'message'=>'Expire Coupon',
            );
            return response()->json($response);
        }

        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
         $coupon =  \Stripe\Coupon::retrieve($re->coupon,[]);

        }catch (\Stripe\Error\RateLimit $e)
        {
            $response = array(
                'error'=>1,
                'message'=>'Invalid Coupon',
            );
            return response()->json($response);
        }catch (\Stripe\Error\InvalidRequest $e)
        {
            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        }

        $id = Auth::id();
        $user = User::find($id);
        $planToken = $re->plan_id;
        $sixMonts = env('STRIPE_PLAN_1_YEAR');
        $oneYear  = env('STRIPE_PLAN_1_YEAR_PLUS');
        if($sixMonts == $planToken)
        {
            $miles=10;
            $counter=2;
        }else{
            $miles=10;
            $counter=4;
        }

        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

//       $token= \Stripe\Subscription::create();
            if ($get = \Stripe\Subscription::create([
                "customer" => $user->stripe_id,
                "items" => [
                    [
                        "plan" => $planToken,

                    ],

                ],
                'coupon'=>$coupon->id,

            ])) {


//                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
                if($get->plan->amount == null){
                    $get->plan->amount = 999;
                }
                $data['amount'] = $plan_amount = $get->plan->amount;
                $payement = new Payment();
                $payement->user_id = $user->id;
                $payement->amount = $plan_amount;
                $payement->charge_id = $get->id;
                $payement->save();
                $subscription = new Subscription;
                $subscription->name = 'RoadSide';
                $subscription->user_id = $id;
                $subscription->stripe_id = $get->id;
                $subscription->stripe_plan = $get->plan->id;
                $subscription->total_miles = $miles;
                $subscription->counter = $counter;
                $subscription->status = 1;
                $timestamp = $get->current_period_end;
                $subscription->ends_at = date('Y-m-d H:i:s', $timestamp);
//                $subscription->trial_ends_at =date('Y-m-d H﻿﻿', $timestamp);
//                $subscription->ends_at =date('Y-m-d H﻿﻿', $timestamp);
                $subscription->quantity = $get->quantity;
                $subscription->save();
                $data['stripe_detail'] = $get;
                $data['success'] = true;
                $data['user'] = $user;


//                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
            }
        } catch (\Stripe\Error\Card $e) {
//                Session::flash('error', $e->getMessage());
//                return redirect()->back()->withInput();

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\RateLimit $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\InvalidRequest $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\Authentication $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\ApiConnection $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\Base $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (Exception $e) {
            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        }
        if(isset($user->linked_id)){
            $user->linked_id = null;
            $user->save();
        }
        $response = array(
            'error'=>0,
            'message'=>'$e->getMessage()',
        );
        return response()->json($response);
    }
    public function createSubUserwithcoupon1(Request $re)
    {
        $coupon = $re->coupon;

        if($coupon == Null)
        {
            $response = array(
                'error'=>1,
                'message'=>'Invalid Coupon',
            );
            return response()->json($response);
        }
        $coupon = coupon::where('coupon',$coupon)->first();
        if(!isset($coupon->valid))
        {
            $response = array(
                'error'=>1,
                'message'=>'Invalid Coupon',
            );
            return response()->json($response);
        }
        $expirey = $coupon->valid;
        $current = strtotime(date("Y-m-d H:i:s"));
        if($expirey < $current)
        {
            $response = array(
                'error'=>1,
                'message'=>'Expire Coupon',
            );
            return response()->json($response);
        }
        if ($re->plan_id == '10MilesMonthlyPlan') {
            $planToken = env('STRIPE_PLAN_MONTHLY_PLAN');
            $miles=10;
            $counter=4;

        } else if ($re->plan_id == '10MilesPlusYearFamilyPlan') {
            $planToken = env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN');
            $miles=10;
            $counter=4;
        }else if ($re->plan_id == '10MilesPlusYear') {
            $planToken = env('STRIPE_PLAN_1_YEAR_PLUS');
            $miles=10;
            $counter=4;
        } else if ($re->plan_id == '10MilesYear') {
            $planToken = env('STRIPE_PLAN_1_YEAR');
            $miles=10;
            $counter=2;
        } else if ($re->plan_id == '6Months') {
            $planToken = env('STRIPE_PLAN_6_MONTHS');
            $miles = 10;
            $counter = 1;
        }
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
         $coupon =  \Stripe\Coupon::retrieve($re->coupon,[]);

        }catch (\Stripe\Error\RateLimit $e)
        {
            $response = array(
                'error'=>1,
                'message'=>'Invalid Coupon',
            );
            return response()->json($response);
        }catch (\Stripe\Error\InvalidRequest $e)
        {
            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        }

        $id = Auth::id();
        $user = User::find($id);
        $planToken = $planToken;

        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

//       $token= \Stripe\Subscription::create();
            if ($get = \Stripe\Subscription::create([
                "customer" => $user->stripe_id,
                "items" => [
                    [
                        "plan" => $planToken,

                    ],

                ],
                'coupon'=>$coupon->id,

            ])) {


//                $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);
                if($get->plan->amount == null){
                    $get->plan->amount = 999;
                }
                $data['amount'] = $plan_amount = $get->plan->amount;
                $payement = new Payment();
                $payement->user_id = $user->id;
                $payement->amount = $plan_amount;
                $payement->charge_id = $get->id;
                $payement->save();
                $subscription = new Subscription;
                $subscription->name = 'RoadSide';
                $subscription->user_id = $id;
                $subscription->stripe_id = $get->id;
                $subscription->stripe_plan = $get->plan->id;
                $subscription->total_miles = $miles;
                $subscription->counter = $counter;
                $subscription->status = 1;
                $timestamp = $get->current_period_end;
                $subscription->ends_at = date('Y-m-d H:i:s', $timestamp);
//                $subscription->trial_ends_at =date('Y-m-d H﻿﻿', $timestamp);
//                $subscription->ends_at =date('Y-m-d H﻿﻿', $timestamp);
                $subscription->quantity = $get->quantity;
                $subscription->save();
                $data['stripe_detail'] = $get;
                $data['success'] = true;
                $data['user'] = $user;


//                return Response::json(array('success' => true, 'message' => 'User Registered Successfully', 'stripe_customer_id'=> $user->stripe_id, 'stripe_charge_id' =>$get->stripe_id ), 200);
            }
        } catch (\Stripe\Error\Card $e) {
//                Session::flash('error', $e->getMessage());
//                return redirect()->back()->withInput();

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Stripe\Error\RateLimit $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\InvalidRequest $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\Authentication $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\ApiConnection $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (\Stripe\Error\Base $e) {

            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        } catch (Exception $e) {
            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        }
        if(isset($user->linked_id)){
            $user->linked_id = null;
            $user->save();
        }
        $response = array(
            'error'=>0,
            'message'=>'$e->getMessage()',
        );
        return response()->json($response);
    }

    //

    public function checkValidCoupon(Request $re)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
          $stripCoupon = \Stripe\Coupon::retrieve($re->coupon, []);
            $response = array(
                'error' => 0,
                'message' => 'Invalid Coupon',
            );
            return response()->json($response);

        } catch (\Stripe\Error\RateLimit $e) {
            $response = array(
                'error' => 1,
                'message' => 'Invalid Coupon',
            );
            return response()->json($response);
        } catch (\Stripe\Error\InvalidRequest $e) {
            $response = array(
                'error' => 1,
                'message' => $e->getMessage(),
            );
            return response()->json($response);
        }
    }

    public function checkemail(Request $re)
    {
        $user = User::where('email',$re->email)->first();
        if(isset($user->id))
        {
            $response = array(
                'error'=>1,
                'message'=>'Email is already in use.',
            );
            return response($response);
        }else{
            $response = array(
                'error'=>0,
                'message'=>'Email is already in use.',
            );
            return response($response);
        }
    }


    public function checkCouponUser(Request $re)
    {
        $coupon = $re->coupon;
        $coupon = coupon::where('coupon',$coupon)->first();
        if (!isset($coupon))
        {
            $response = array(
                'error'=>1,
                'message'=>'Invalid Coupon',
            );
            return response()->json($response);
        }
        $expirey = $coupon->valid;
        $current = strtotime(date("Y-m-d H:i:s"));
//        if($expirey < $current)
//        {
//            $response = array(
//                'error'=>1,
//                'message'=>'Expire Coupon',
//            );
//            return response()->json($response);
//        }
        if($coupon == Null)
        {
            $response = array(
                'error'=>1,
                'message'=>'Invalid Coupon',
            );
            return response()->json($response);
        }
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $coupon =  \Stripe\Coupon::retrieve($re->coupon,[]);
            $response = array(
                'error'=>0,
                'message'=>$coupon->percent_off,
            );
            return response()->json($response);

        }catch (\Stripe\Error\RateLimit $e)
        {
            $response = array(
                'error'=>1,
                'message'=>'Invalid Coupon',
            );
            return response($response);
        }catch (\Stripe\Error\InvalidRequest $e)
        {
            $response = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
            return response()->json($response);
        }
    }
}
//info@driveroadside.com
