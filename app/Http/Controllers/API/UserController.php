<?php

// namespace App\Http\Controllers;

namespace App\Http\Controllers\API;

use App\Chat;
use App\ChatMember;
use App\Http\Controllers\NotificationsController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\User;
use App\Cars;
use App\Subscription;
use App\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use File;
use Carbon\Carbon;
use App\Jobs;
use App\Service;

class UserController extends Controller {

    public $successStatus = 200;

    /**
     * login api
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return sendError('Invalid Username or Password', 401);
            // return response()->json(['error' => $validator->errors()], 401);
        }
        $swoopResult = json_decode($this->checkSwoopTocken());
        if ($swoopResult->errors[0]->message && $swoopResult->errors[0]->message == 'authentication failure') {
            $newToken = json_decode($this->genrateNewSwoopToken());
            $newToken = $newToken->access_token;

            $swoopToken = \App\SwoopToken::first();
            $swoopToken->token = $newToken;
            $swoopToken->save();
        }

        $sToken = \App\SwoopToken::first();
        $sToken = $sToken->token;

        DB::beginTransaction();
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            // $user->token = bcrypt(time());
            // $updatedUser = User::find(Auth::user()->id);
            // $user->last_session = str_random(15);
            $user->session_token = $user->createToken('RoadSide')->accessToken;
            $user->save();
            $id = $user->id;
            $user['data'] = User::with('getSubscription')->where('id', $id)->orderBy('id', 'DESC')->first();

            if ($user['data']->linked_id != null) {
                $create_date = $user['data']->created_at;
                $initial_time_check = Carbon::parse($create_date)->addHours(48);
                if(Carbon::now() < $initial_time_check){
                    DB::rollBack();
                    $date_time = Carbon::parse($initial_time_check)->format('Y-m-d H:i:s');
                    return sendError("Your account will be activated after ($date_time).", 200);
                }
            }

            if (!empty($user['data']->getSubscription)) {

                $plan = $user['data']->getSubscription->stripe_plan;

                if ($plan == env('STRIPE_PLAN_MONTHLY_PLAN')) {
                    $user['data']->getSubscription->level = 'Level 5';
                } else if ($plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')) {
                    $user['data']->getSubscription->level = 'Level 4';
                } else if ($plan == env('STRIPE_PLAN_1_YEAR_PLUS')) {
                    $user['data']->getSubscription->level = 'Level 3';
                } else if ($plan == env('STRIPE_PLAN_1_YEAR')) {
                    $user['data']->getSubscription->level = 'Level 2';
                } else if ($plan == env('STRIPE_PLAN_6_MONTHS')) {
                    $user['data']->getSubscription->level = 'Level 1';
                }
            }

            $user['data']->swoopAccessToken = $sToken;
            $user['data']->session_token = $user->session_token;
            $success = $user['data'];
            DB::commit();
            return sendSuccess('Login Successful.', $success);
        } else {
            return sendError('Login Failed.', 401);
        }
    }

    function genrateNewSwoopToken() {

        //Test tocken
        //cf850118afacbcacb7bca45be51bd6723d003332bc14c4f070823ef9993fa808
        //SVibvXODSKdRylw-ARqqI3rJAxcenTstryNKCjPTLWg
//        $fields = array(
//            'client_id' => "c6d76652409e2ddb7551efa1e8df7c66079c148698f54120f76f5a7a0b8d9769",
//            'grant_type' => "client_credentials",
//            'client_secret' => "cf850118afacbcacb7bca45be51bd6723d003332bc14c4f070823ef9993fa808",
//            'scope' => "fleet"
//        );
        $fields = array(
            'client_id' => "7rqMIRMDRlzKXX5icVge2n4JYp0iITj-by8z1ZyFNFc",
            'grant_type' => "client_credentials",
            'client_secret' => "SVibvXODSKdRylw-ARqqI3rJAxcenTstryNKCjPTLWg",
            'scope' => "fleet"
        );

        $fields = json_encode($fields);
        // print("\nJSON sent:\n");
        // print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://app.joinswoop.com/oauth/token");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    function checkSwoopTocken() {

        $fields = array(
            'query' => "test"
        );

        $fields = json_encode($fields);
        // print("\nJSON sent:\n");
        // print($fields);

        $swoopToken = \App\SwoopToken::first();


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://app.joinswoop.com/graphql");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Authorization: Bearer ' . $swoopToken->token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function getSwoopToken(Request $request) {
      $swoopResult = json_decode($this->checkSwoopTocken());
        if ($swoopResult->errors[0]->message && $swoopResult->errors[0]->message == 'authentication failure') {
            $newToken = json_decode($this->genrateNewSwoopToken());
            $newToken = $newToken->access_token;

            $swoopToken = \App\SwoopToken::first();
            $swoopToken->token = $newToken;
            $swoopToken->save();
        }

        $swoopToken = \App\SwoopToken::first();
        return sendSuccess('Swoop Token', $swoopToken->token);
    }

    /**
     * Register api
     *
     * @return JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'contact_number' => 'required',
          //  'stripe_plan' => 'required',
          //  'subscription_id' => 'required',
          //  'ends_at' => 'required',
          //  'trips' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        // Create new user here
        $user = User::create($input);
        $user->session_token = $user->createToken('RoadSide')->accessToken;
        if($request->type && !empty($request->type)){
            $user->type =  $request->type;
        }
        $user->save();

        if(isset($request->stripe_plan) && isset($request->subscription_id)){

            // create new subscription for user API
            $sub = new Subscription();
            $sub->user_id = $user->id;
            $sub->name = 'RoadSide';
            $sub->stripe_id = $request->subscription_id;
            $sub->stripe_plan = $request->stripe_plan;
            $sub->ends_at = $request->ends_at;
            $sub->status = 1;
            $sub->counter = $request->trips;
            $sub->save();

        }

        $id = $user->id;
        $user['data'] = User::with('getSubscription')->where('id', $id)->orderBy('id', 'DESC')->get();
        $success = $user;

        return sendSuccess('Registeration Successfull', $success);
    }

public function register_guest(Request $request){
 $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'contact_number' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 401);
        }

        $input = $request->all();

        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user->session_token = $user->createToken('RoadSide')->accessToken;
        if($request->type && !empty($request->type)){
            $user->type =  $request->type;
        }

        if($user->save()) {
            $group_chat           = new Chat;
            $group_chat->admin_id = $user->id;
            $group_chat->type     = 'group';
            $group_chat->save();
        }
        $id = $user->id;
        $user['data'] = User::with('getSubscription')->where('id', $id)->first();
        $user['data']->group_chat_id = $group_chat ? $group_chat->id : null;
        $success = $user;

        return sendSuccess('Registration Successful', $success);
    }


    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details() {
        $user = Auth::user();
        $id = $user->id;
        $data['data'] = User::with('getSubscription')->where('id', $id)->orderBy('id', 'DESC')->first();
        if (!empty($data['data']->getSubscription)) {
            $plan = $data['data']->getSubscription->stripe_plan;

            if ($plan == env('STRIPE_PLAN_MONTHLY_PLAN')) {
                $data['data']->getSubscription->level = 'Level 5';
            } else if ($plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')) {
                $data['data']->getSubscription->level = 'Level 4';
                $data['data']->getSubscription->total_count = 4;
            }else if ($plan == env('STRIPE_PLAN_1_YEAR_PLUS')) {
                $data['data']->getSubscription->level = 'Level 3';
                $data['data']->getSubscription->total_count = 4;
            } else if ($plan == env('STRIPE_PLAN_1_YEAR')) {
                $data['data']->getSubscription->level = 'Level 2';
                $data['data']->getSubscription->total_count = 2;
            } else if ($plan == env('STRIPE_PLAN_6_MONTHS')) {
                $data['data']->getSubscription->total_count = 1;
            }
        }

        return sendSuccess('Current User Detail', $data['data']);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return response()->json([
                    'message' => 'Successfully logged out'
        ]);
    }

    public function updateUserProfile(Request $request) {
        $validator = Validator::make($request->all(), [
            'new_username' => 'required',
            'contact_number' => 'required',
            'address' => 'required',
            'zipcode' => 'required',
            'state' => 'required',
            'country' => 'required',
            'city' => 'required'
        ]);
        if ($validator->fails()) {
            return sendError($validator->errors(), 401);
        }
        $user = User::find(Auth::user()->id);
        $user->name = $request->new_username;
        $user->contact_number = $request->contact_number;
        $user->address = $request->address;
        $user->zipcode = $request->zipcode;
        $user->state = $request->state;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->save();
        return sendSuccess('Profile Updated Successfully', $user);
    }

    public function updateUserProfileForGuest(Request $request) {
        $validator = Validator::make($request->all(), [
                    'new_username' => 'required',
                    'contact_number' => 'required',
                    'email' => 'required',
        ]);
        if ($validator->fails()) {
            return sendError($validator->errors(), 401);
        }
        $usercheck = User::where('email', $request->email)->first();
        if(!empty($usercheck)) {
            if ($usercheck->id != Auth::user()->id) {
                return sendError('Email already in use. Please use a different email.', 401);
            }
        }
        $user = User::find(Auth::user()->id);
        $user->name = $request->new_username;
        $user->contact_number = $request->contact_number;
        $user->email = $request->email;
        $user->save();
        return sendSuccess('Profile Updated Successfully', $user);
    }

    public function updateAvatar(Request $request) {

        $validator = Validator::make($request->all(), [
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return sendError($validator->errors(), 401);
        }

        $user = User::find(Auth::user()->id);
        $path = public_path() . '/svg/';
        File::delete($path . $user->avatar);
        $image = $request->file('image');
        $filename = str_random(10) . '.' . $image->getClientOriginalExtension();
        $image->move($path, $filename);
        $user->avatar = $filename;
        $user->save();
        return sendSuccess('Profile Image Successfully Changed', $user);
    }

    function changePassword(Request $request) {
        $validator = Validator::make($request->all(), [
                    'current_password' => 'required',
                    'password' => 'required'
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 405);
        }
        $password = Auth::user()->password;
        if (Hash::check($request['current_password'], $password)) {
            $newpass = Hash::make($request['password']);
            User::where('id', Auth::user()->id)->update(['password' => $newpass]);
            return sendSuccess('Password updated successfully!', null);
        } else {
            return sendError('Invalid old password!', 405);
        }
    }

    public function create_subscription(Request $request) {
        $validator = Validator::make($request->all(), [
                    'token' => 'required',
                    'plan_id' => 'required'
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 405);
        }

        $id = Auth::user()->id;
        $token = $request->token;
        $plan_id = $request->plan_id;
        $user = User::find($id);
        $subscription_exit = Subscription::where('user_id', $id)->first();
        if (!$subscription_exit) {
//            dd('Condtion wrong');
            if ($plan_id == 1) {
                try {
                    $get = $user->newSubscription('RoadSide', env('STRIPE_PLAN_6_MONTHS'))->create($token);
//                    $user->newSubscription('RoadSide', env('STRIPE_PLAN_6_MONTHS'))->create($token);
//                    dd($get->stripe_id);
                    $id = Auth::user()->id;
                    $subscription_id = $get->stripe_id;

                    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                    $stripe_details = \Stripe\Subscription::retrieve($subscription_id);
                    $plan_amount = $stripe_details->plan->amount;
                    $timestamp = $stripe_details->current_period_end;
                    $periods_end = date('Y-m-d H:i:s', $timestamp);
                    $subscription = Subscription::where('user_id', $id)->where('stripe_id', $subscription_id)->first();
                    $subscription->ends_at = $periods_end;
                    $subscription->status = 1;
                    $subscription->save();


                    $stripe_details = \Stripe\Subscription::retrieve($subscription_id);
//                   $plan_name = $stripe_details->plan->nickname;
                    $plan_amount = $stripe_details->plan->amount;

                    $payement = new Payment();
                    $payement->user_id = $id;
                    $payement->amount = $plan_amount;
                    $payement->charge_id = $subscription_id;
                    $payement->save();

                    return sendSuccess('Subscription Create Successfully!', null);
                } catch (Exception $e) {
                    echo 'Message: ' . $e->getMessage();
                } catch (\Stripe\Error\Base $e) {
                    echo($e->getMessage());
                }
            } else if ($plan_id == 2) {
                try {
                    $get = $user->newSubscription('RoadSide', env('STRIPE_PLAN_1_YEAR'))->create($token);

                    $id = Auth::user()->id;
                    $subscription_id = $get->stripe_id;

                    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                    $stripe_details = \Stripe\Subscription::retrieve($subscription_id);
                    $plan_amount = $stripe_details->plan->amount;
                    $timestamp = $stripe_details->current_period_end;
                    $periods_end = date('Y-m-d H:i:s', $timestamp);

                    $subscription = Subscription::where('user_id', $id)->where('stripe_id', $subscription_id)->first();
                    $subscription->ends_at = $periods_end;
                    $subscription->status = 1;
                    $subscription->save();


                    $stripe_details = \Stripe\Subscription::retrieve($subscription_id);
                    $plan_amount = $stripe_details->plan->amount;

                    $payement = new Payment();
                    $payement->user_id = $id;
                    $payement->amount = $plan_amount;
                    $payement->charge_id = $subscription_id;
                    $payement->save();

                    return sendSuccess('Subscription Create Successfully!', null);
                } catch (Exception $e) {
                    echo 'Message: ' . $e->getMessage();
                } catch (\Stripe\Error\Base $e) {
                    echo($e->getMessage());
                }
            } else if ($plan_id == 3) {
                try {
                    $get = $user->newSubscription('RoadSide', env('STRIPE_PLAN_1_YEAR_PLUS'))->create($token);

                    $id = Auth::user()->id;
                    $subscription_id = $get->stripe_id;

                    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                    $stripe_details = \Stripe\Subscription::retrieve($subscription_id);
                    $plan_amount = $stripe_details->plan->amount;
                    $timestamp = $stripe_details->current_period_end;
                    $periods_end = date('Y-m-d H:i:s', $timestamp);

                    $subscription = Subscription::where('user_id', $id)->where('stripe_id', $subscription_id)->first();
                    $subscription->ends_at = $periods_end;
                    $subscription->status = 1;
                    $subscription->save();


                    $stripe_details = \Stripe\Subscription::retrieve($subscription_id);
                    $plan_amount = $stripe_details->plan->amount;

                    $payement = new Payment();
                    $payement->user_id = $id;
                    $payement->amount = $plan_amount;
                    $payement->charge_id = $subscription_id;
                    $payement->save();

                    return sendSuccess('Subscription Create Successfully!', null);
                } catch (Exception $e) {
                    echo 'Message: ' . $e->getMessage();
                } catch (\Stripe\Error\Base $e) {
                    echo($e->getMessage());
                }
            } else if ($plan_id == 4) {
                try {
                    $get = $user->newSubscription('RoadSide', env('STRIPE_PLAN_1_YEAR_PLUS'))->create($token);

                    $id = Auth::user()->id;
                    $subscription_id = $get->stripe_id;

                    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                    $stripe_details = \Stripe\Subscription::retrieve($subscription_id);
                    $plan_amount = $stripe_details->plan->amount;
                    $timestamp = $stripe_details->current_period_end;
                    $periods_end = date('Y-m-d H:i:s', $timestamp);

                    $subscription = Subscription::where('user_id', $id)->where('stripe_id', $subscription_id)->first();
                    $subscription->ends_at = $periods_end;
                    $subscription->status = 1;
                    $subscription->save();


                    $stripe_details = \Stripe\Subscription::retrieve($subscription_id);
                    $plan_amount = $stripe_details->plan->amount;

                    $payement = new Payment();
                    $payement->user_id = $id;
                    $payement->amount = $plan_amount;
                    $payement->charge_id = $subscription_id;
                    $payement->save();

                    return sendSuccess('Subscription Create Successfully!', null);
                } catch (Exception $e) {
                    echo 'Message: ' . $e->getMessage();
                } catch (\Stripe\Error\Base $e) {
                    echo($e->getMessage());
                }
            } else {
                return sendError('Invalid Plan_id', 405);
            }
        } else {
            return sendError('This user already have plan', 405);
        }
    }

    public function create_guest_service(Request $request) {
        $validator = Validator::make($request->all(), [
                    'token' => 'required',
                    'name' => 'required'
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 405);
        }

        $id = Auth::user()->id;
        $token = $request->token;
        $name = $request->name;
        $user = User::find($id);

        //name can be from these = {locksmith,   tire_change,   fuel_delivery,   tow,    jumpstart }

        if (!empty($name)) {
            if ($name == 'tow') {

                $service = new Service();
                $service->user_id = $id;
                $service->name = $name;
                $service->save();

                return sendSuccess('Service Create Successfully!', null);
            } else {
                $amount = 75;

                try {
                    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                    $charge = \Stripe\Charge::create([
                                'amount' => $amount*100,
                                'currency' => 'usd',
                                'description' => ucwords($name) . ' Service',
                                'source' => $token,
                    ]);

                    $service = new Service();
                    $service->user_id = $id;
                    $service->name = $name;
                    $service->amount = $charge->amount;
                    $service->save();

                    $payement = new Payment();
                    $payement->user_id = $id;
                    $payement->amount = $charge->amount;
                    $payement->charge_id = $charge->id;
                    $payement->save();


                    return sendSuccess('Service Create Successfully!', null);
                } catch (Exception $e) {
                    echo 'Message: ' . $e->getMessage();
                } catch (\Stripe\Error\Base $e) {
                    echo($e->getMessage());
                }
            }
        } else {
            return sendError('Invalid Plan_id', 405);
        }
    }

    public function retrieve_job_id(Request $request) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'job_id' => 'required',
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 405);
        }

        $id = Auth::user()->id;
        $name = $request->name;
        $job_id = Jobs::where('job_id', $request->job_id)->first();
        $user = User::find($id);

        //name = {locksmith,   tire_change,   fuel_delivery,   tow,    jumpstart }

        if (!empty($name)) {
            $service = Service::where('user_id', $id)->where('job_id', null)->where('name', $name)->where('status', 1)->first();
            $service->job_id = $job_id->id;
            $service->save();
            return sendSuccess('Job Id Assigned Successfully!', null);
        } else {
            return sendError('Invalid Name', 405);
        }
    }

    public function retrieve_miles_for_members(Request $request) {
        $validator = Validator::make($request->all(), [
                    'miles' => 'required',
                    'job_id' => 'required',
                    'is_allowed' => 'required',
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 405);
        }


        $id = Auth::user()->id;
        $is_allowed = $request->is_allowed;
        $miles = (double) $request->miles;
        $user = User::find($id);
        $job = Jobs::where('job_id', $request->job_id)->first();
        $sub_id = Subscription::find($user->getSubscription->id);
        $miles_allowed = $sub_id->total_miles;
//
//        if(!empty($user->getSubscription) && $userdetail->getSubscription->counter == 0){
//            $message = 'You have 0 trips remaining.';
//            $data['miles_exceeded'] = false;
//            return sendSuccess($message, $data);
//        }

        if (!empty($miles)) {
            if ($miles > $miles_allowed) {
                $amount = (($miles - $miles_allowed) * 5);
            }
            if ($is_allowed == 0 && $miles > $miles_allowed) {
                $message = ' Miles exceeded from your Membership Plan!. Do you want to continue with $5 per mile for extra miles?';
                $data['miles_exceeded'] = true;
                $data['message'] = ($miles - $miles_allowed) . $message;
                return sendSuccess($message, $data);
            } else if ($is_allowed == 0 && $miles <= $miles_allowed) {

                Subscription::where('id', $user->getSubscription->id)->update(['counter' => $user->getSubscription->counter - 1]);
                $service = Service::where('user_id', $id)->where('job_id', $job->id)->where('sub_id', $user->getSubscription->id)->where('name', 'tow')->first();
                $service->miles_covered = $miles;
                $service->status = 1;
                $service->save();

                $message = 'You have successfully availed your service through membership.';
                $data['miles_exceeded'] = false;
                $data['message'] = $message;
                return sendSuccess($message, $data);
            } else if ($is_allowed == 1) {

                $service = Service::where('user_id', $id)->where('job_id', $job->id)->where('sub_id', $user->getSubscription->id)->where('name', 'tow')->first();
                if (!empty($service) && $service->status != 1) {

                    try {
                        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                        $charge = \Stripe\Charge::create([
                                    'amount' => $amount * 100,
                                    'currency' => 'usd',
                                    'description' => 'Tow Service Extended Membership Service',
                                    'customer' => $user->stripe_id,
                        ]);

                        Subscription::where('id', $user->getSubscription->id)->update(['counter' => $user->getSubscription->counter - 1]);

                        $service->miles_covered = $miles;
                        $service->amount = $amount;
                        $service->status = 1;
                        $service->save();

                        $payement = new Payment();
                        $payement->user_id = $id;
                        $payement->amount = $charge->amount;
                        $payement->charge_id = $charge->id;
                        $payement->save();

                        $message = 'Members Tow Miles Added with amount Successfully!';
                        $data['miles_exceeded'] = false;
                        $data['message'] = $message;
                        return sendSuccess($message, $data);
                    } catch (Exception $e) {
                        echo 'Message: ' . $e->getMessage();
                    } catch (\Stripe\Error\Base $e) {
                        echo($e->getMessage());
                    }
                }
//                    else {
//                        $message = 'Members Tow Miles Added with amount Successfully!';
//                        $data['miles_exceeded'] = 2;
//                        $data['message'] = $message;
//                        return sendSuccess($message, $data);
//                    }
            } else if ($is_allowed == 2) {
                Service::where('user_id', $id)->where('job_id', $job->id)->where('name', 'tow')->delete();
//                    Subscription::where('id', $user->getSubscription->id)->update(['counter' => $user->getSubscription->counter + 1]);
                $message = 'You opt to not use this service at the moment.';
                $data['miles_exceeded'] = 2;
                $data['message'] = $message;
                return sendSuccess($message, $data);
            }
        } else {
            return sendError('Invalid Service Type', 405);
        }
    }

    public function pay_per_use_member(Request $request) {
        $validator = Validator::make($request->all(), [
                    'miles' => 'required',
                    'name' => 'required',
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 405);
        }


        $id = Auth::user()->id;
        $miles = (double) $request->miles;
        $user = User::find($id);
        $sub_id = Subscription::find($user->getSubscription->id);
        $miles_allowed = $sub_id->total_miles;
        $name = $request->name;

        if ($name == 'tow') {
            $amount = 99;
        } else {
            $amount = 75;
        }

        if ($miles > 0) {
            $amount = $amount + (($miles) * 5);
        }

        if (!empty($sub_id)) {

            try {
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                $charge = \Stripe\Charge::create([
                            'amount' => $amount * 100,
                            'currency' => 'usd',
                            'description' => $name . ' Service Pay Per Use',
                            'customer' => $user->stripe_id,
                ]);

                $payement = new Payment();
                $payement->user_id = $id;
                $payement->amount = $charge->amount;
                $payement->charge_id = $charge->id;
                $payement->save();

                $message = 'Members Service added with amount Successfully!';

                $data['message'] = $message;
                return sendSuccess($message, $data);
            } catch (Exception $e) {
                echo 'Message: ' . $e->getMessage();
            } catch (\Stripe\Error\Base $e) {
                echo($e->getMessage());
            }
        }
    }

    public function retrieve_miles_for_service(Request $request) {
        $validator = Validator::make($request->all(), [
                    'token' => 'required',
                    'name' => 'required',
                    'miles' => 'required',
//                    'job_id' => 'required',
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 405);
        }

        $id = Auth::user()->id;
        $token = $request->token;
        $name = $request->name;
        $miles = 0;
        if(isset($request->miles) && !empty($request->miles) ) {
            $miles = (double)$request->miles;
        }
        $user = User::find($id);


        //name = {locksmith,   tire_change,   fuel_delivery,   tow,    jumpstart }

        if (!empty($name)) {
            if ($name == 'tow') {
                $amount = 99;
                if ($miles > 0) {
                    $amount = $amount + ($miles * 5);
                }
                try {
                    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                    if($request->address){

                        $charge = \Stripe\Charge::create([
                            'amount' => $amount * 100,
                            'currency' => 'usd',
                            'description' => ucwords($name) . ' Service',
                            'source' => $token,
                            'shipping' => [
                                'address' => [
                                    "city"=> $request->city,
                                    "country"=> $request->country,
                                    "line1"=> $request->address,
                                    "line2"=> null,
                                    "postal_code"=> $request->zip,
                                    "state"=> $request->state
                                ],
                                'name' => $user->name
                            ]
                        ]);

                    } else {

                        $charge = \Stripe\Charge::create([
                            'amount' => $amount * 100,
                            'currency' => 'usd',
                            'description' => ucwords($name) . ' Service',
                            'source' => $token,
                        ]);
                    }

                    if(isset($request->job_id) && !empty($request->job_id) ) {
                        $job = Jobs::where('job_id', $request->job_id)->first();
                        $service = Service::where('user_id', $id)->where('job_id', $job->id)->where('name', $name)->first();
                        if($service) {
                            $service->miles_covered = $miles;
                            $service->amount = $amount;
                            $service->status = 1;
                            $service->save();
                        }
                    }

                    $payement = new Payment();
                    $payement->user_id = $id;
                    $payement->amount = $charge->amount;
                    $payement->charge_id = $charge->id;
                    $payement->save();


                    return sendSuccess('Miles Added with amount Successfully!', null);
                } catch (Exception $e) {
                    echo 'Message: ' . $e->getMessage();
                } catch (\Stripe\Error\Base $e) {
                    echo($e->getMessage());
                }
            } else {
                return sendError('Invalid Service Type', 405);
            }
        } else {
            return sendError('Invalid Service Type', 405);
        }
    }

    public function get_subscription_plan() {

        $id = Auth::user()->id;

        $stripe_id = Subscription::where('user_id', $id)->first();
        if ($stripe_id) {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $signle_plan['subscription'] = \Stripe\Subscription::retrieve($stripe_id->stripe_id);

            return sendSuccess('Subscription Plan', $signle_plan);
        } else {
            return sendError('No subscription plan', 405);
        }
    }

    public function cancel_subscription(Request $request) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);
            if ($validator->fails()) {
                $messages = $validator->messages()->all();
                $messages = join("\n", $messages);
                return sendError($messages, 405);
            }
            try {

                $user = User::where('email', $request->email)->with('getSubscription')->first();
                if(empty($user)){
                    return sendError('User not found.', 405);
                }
                $subscription = Subscription::where('user_id', $user->id)->where('is_cancelled',0)->first();
                if(!isset($subscription->stripe_plan)){
                    return sendError('You haven\'t subscribed to any plan yet.' , 405);
                }
                if($subscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')){
//                    User::where('linked_id', $user->id)->update(['linked_id' => null]);
                }
//
//                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
//                $sub = \Stripe\Subscription::retrieve($subscription->stripe_id);
//                $sub->cancel();

                $user->subscription('RoadSide')->cancelNow();

                $subscription->is_cancelled =1;
                $subscription->save();
                return sendSuccess('Subscription cancel successfully!!!', null);

            } catch (Exception $e) {
                return sendError($e->getMessage(), 405);
            } catch (\Stripe\Error\Base $e) {
                return sendError($e->getMessage(), 405);
            }
    }

    public function get_cancel_subscriptions() {

            $subscriptions = Subscription::with('getUser')->where('is_cancelled', 1)->get();
            return sendSuccess('Cancelled Subscriptions', $subscriptions);
    }

    public function create_charge(Request $request) {
        $validator = Validator::make($request->all(), [
                    'token' => 'required',
                    'amount' => 'required'
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 405);
        }
        $token = $request->token;
        $amount = $request->amount;

        $id = Auth::user()->id;
        $subscription = Subscription::where('user_id', $id)->first();
        if ($subscription) {
            return sendError('Already have subscription', 405);
        } else {
            try {
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                $charge = \Stripe\Charge::create([
                            'amount' => $amount*100,
                            'currency' => 'usd',
                            'description' => 'Test charge',
                            'source' => $token,
                ]);

                $payement = new Payment();
                $payement->user_id = $id;
                $payement->amount = $charge->amount;
                $payement->charge_id = $charge->id;
                $payement->save();
                return sendSuccess('Charge succesfully!!!', $charge);
            } catch (Exception $e) {
                echo 'Message: ' . $e->getMessage();
            } catch (\Stripe\Error\Base $e) {
                echo($e->getMessage());
            }
        }
    }

    function adminCancelSub(Request $request) {

        $id = $request->id;
        $user = User::find($id);
        $stripe_id = Subscription::where('user_id', $id)->where('status', '1')->first();

        if ($stripe_id) {

            try {

                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                $sub = \Stripe\Subscription::retrieve($stripe_id->stripe_id);
                $sub->cancel();
//              $user->subscription('main')->cancel();
//                $stripe_id->delete();
                //$stripe_id = $stripe_id->stripe_id;
                //$subscription = Subscription::where('user_id',$id)->where('stripe_id',$stripe_id)->first();
                $stripe_id->status = 0;
                $stripe_id->save();
                return 1;
            } catch (Exception $e) {
                echo 'Message: ' . $e->getMessage();
            } catch (\Stripe\Error\Base $e) {
                echo($e->getMessage());
            }
        } else {
            return sendError('No subscription plan', 405);
        }
    }

    function userDetail($id) {

        $data['tab'] = '';
        $data['title'] = 'Details';
        $data['user'] = User::with('getSubscription', 'getPaymnet')->where('id', $id)->orderBy('id', 'DESC')->get();

        return view('admin.user-detail', $data);
    }

    function usedServices($id) {

        $data['tab'] = '';
        $data['title'] = 'Used Services';
        $data['user'] = User::with('getJob')->where('id', $id)->orderBy('id', 'DESC')->get();

        return view('admin.used-services', $data);
    }







    // New Apis

    public function get_user_subscription_details() {
        if(isset(Auth::user()->linked_id)){
            $user_id = Auth::user()->linked_id;
            $plan_owner = User::where('id', Auth::user()->linked_id)->first();
        }else{
            $user_id = Auth::user()->getAuthIdentifier();
            $plan_owner = null;
        }
        $service_used_by_users = [];
        $res = Subscription::where('user_id', $user_id)->first();

        if(isset($res)){

            $services = Service::where('user_id', $user_id)->where('sub_id', $res->id)->get();
            foreach ($services as $one){
                if($one->used_by == null){
                    $user = User::find($user_id);
                }else{
                    $user = User::find($one->used_by);
                }
                $user['service_details'] = $one->service_details();
                $service_used_by_users[] = $user;
            }

            if ($res->stripe_plan == env('STRIPE_PLAN_MONTHLY_PLAN')) {

                $subscription_date = $res->created_at;
                $trip_date = $res->updated_at;

                $first_span_end_date = Carbon::parse($subscription_date)->addMonths(3);
                $second_span_end_date = Carbon::parse($first_span_end_date)->addMonths(3);
                $third_span_end_date = Carbon::parse($second_span_end_date)->addMonths(3);
                $forth_span_end_date = Carbon::parse($third_span_end_date)->addMonths(3);

               // if($trip_date < $first_span_end_date){
                 //   $next_trip_avaliable_after = Carbon::parse($first_span_end_date)->addDay(1);
                //}
                //if($trip_date < $second_span_end_date){
                  //  $next_trip_avaliable_after = Carbon::parse($second_span_end_date)->addDay(1);
               // }
                //if($trip_date < $third_span_end_date){
                  //  $next_trip_avaliable_after = Carbon::parse($third_span_end_date)->addDay(1);
                //}
                //if($trip_date < $forth_span_end_date){
                  //  $next_trip_avaliable_after = Carbon::parse($forth_span_end_date)->addDay(1);
                //}

                if($trip_date < $first_span_end_date){
                    $next_trip_avaliable_after = $first_span_end_date;
                }
                if($trip_date < $second_span_end_date){
                    $next_trip_avaliable_after = $second_span_end_date;
                }
                if($trip_date < $third_span_end_date){
                    $next_trip_avaliable_after = $third_span_end_date;
                }
                if($trip_date < $forth_span_end_date){
                    $next_trip_avaliable_after = $forth_span_end_date;
                }


                $name = 'Monthly Membership';
                $price = '$ 9.99';
                $total_trips = 4;
                $used_trips = $total_trips - $res->counter;
                $remaining_trips = $total_trips - $used_trips;
                $miles = $res->total_miles;
                $renew_date = $res->ends_at;
                $family_members = null;
                $level = 'Level 5';
                $duration = 'Monthly';
                $status = $res->status;
                $next_trip_date = $next_trip_avaliable_after;
            } else if ($res->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')) {
                $name = '1 Year Membership (Family Plan)';
                $price = '$ 149.99';
                $total_trips = 4;
                $used_trips = $total_trips - $res->counter;
                $remaining_trips = $total_trips - $used_trips;
                $miles = $res->total_miles;
                $renew_date = $res->ends_at;
                $family_members = 3;
                $level = 'Level 4';
                $duration = 'Year';
                $status = $res->status;
                $next_trip_date = null;
            } else if ($res->stripe_plan == env('STRIPE_PLAN_1_YEAR_PLUS')) {
                $name = '1 Year Membership';
                $price = '$ 99.99';
                $total_trips = 4;
                $used_trips = $total_trips - $res->counter;
                $remaining_trips = $total_trips - $used_trips;
                $miles = $res->total_miles;
                $renew_date = $res->ends_at;
                $family_members = null;
                $level = 'Level 3';
                $duration = 'Year';
                $status = $res->status;
                $next_trip_date = null;
            } else if ($res->stripe_plan == env('STRIPE_PLAN_1_YEAR')) {
                $name = '6 Months Membership';
                $price = '$ 59.99';
                $total_trips = 2;
                $used_trips = $total_trips - $res->counter;
                $remaining_trips = $total_trips - $used_trips;
                $miles = $res->total_miles;
                $renew_date = $res->ends_at;
                $family_members = null;
                $level = 'Level 2';
                $duration = '6 Months';
                $status = $res->status;
                $next_trip_date = null;
            }else{
                return sendError('No subscription plan', 200);
            }
        }else{
            return sendError('No subscription plan', 200);
        }
        $subscription = array(
            'name' => $name,
            'price' => $price,
            'total_trips' => $total_trips,
            'used_trips' => $used_trips,
            'remaining_trips' => $remaining_trips,
            'miles' => $miles,
            'renew_date' => $renew_date,
            'family_members' => $family_members,
            'level' => $level,
            'duration' => $level,
            'status' => $status,
            'next_trip_date' => $next_trip_date
        );
        $data['subscription'] = $subscription;
        $data['subscription_canceled'] = $res->is_cancelled;
        $data['subscription']['plan_owner'] = $plan_owner;
        $data['services_used_by'] = $service_used_by_users;

        return sendSuccess('Subscription Plan', $data);

    }

    function register_new_member(Request $request) {
//        != env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')
        if(!isset(Auth::user()->getSubscription )){
            return sendError('Upgrade your membership to have this feature.', 401);
        }

        if (Auth::user()->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')) {
            if(User::where('linked_id', Auth::user()->getAuthIdentifier())->count() >= 3){
                return sendError('You cannot add more than 3 members.', 405);
            }
        } else {
            if(User::where('linked_id', Auth::user()->getAuthIdentifier())->count() >= 1){
                return sendError('You cannot add more than 1 member.', 405);
            }
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
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
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 401);
        }
        $plain_password = $request->password;

        $token = bcrypt(str_random(15));

        $request->merge(['linked_id' => Auth::user()->getAuthIdentifier()]);
        $request->merge(['password' => bcrypt($request->password)]);
        $request->merge(['remember_token' => $token]);

        $user = User::create($request->all());
        $user->session_token = $user->createToken('RoadSide')->accessToken;
        $user->save();

        $member_ids = array();
        array_push($member_ids, Auth::user()->id,$user->id);
        $member_id = $user->id;
        $chat = Chat::with('members.user')->where('type', 'single')->whereHas('members', function ($query) use ($member_id) {
            $query->where('member_id', Auth::user()->id)->orWhere('member_id', $member_id);
            $query->groupBy('id')->havingRaw('COUNT(*) = 2');
        })->first();

        if(!$chat) {
            $chat = new Chat;
            $chat->admin_id = Auth::user()->id;
            $chat->type = 'single';
            $chat->save();

            if($chat){
                foreach($member_ids as $member_id){
                    $member = new ChatMember;
                    $member->chat_id = $chat->id;
                    $member->member_id = $member_id;
                    $member->save();
                }
            }
        }

        $group_chat = Chat::where('admin_id',Auth::user()->id)->where('type', 'group')->first();
        if(!$group_chat) {
            $group_chat           = new Chat;
            $group_chat->admin_id = Auth::user()->id;
            $group_chat->type     = 'group';
            $group_chat->save();
            if($group_chat){
                foreach($member_ids as $member_id){
                    $member = new ChatMember;
                    $member->chat_id = $group_chat->id;
                    $member->member_id = $member_id;
                    $member->save();
                }
            }
        }
        else{
            $member = new ChatMember;
            $member->chat_id = $group_chat->id;
            $member->member_id = $user->id;
            $member->save();
        }

        $plan_members = User::where('linked_id', Auth::user()->getAuthIdentifier())
            ->where('id', '!=', $user->id)->pluck('id')->toArray();

        if (count($plan_members) > 0) {
            foreach($plan_members as $plan_member){
                //Create new chat
                $chat = new Chat;
                $chat->admin_id = Auth::user()->id;
                $chat->type = 'single';
                $chat->save();
                if ($chat) {
                    //Add plan member to this chat
                    $member = new ChatMember;
                    $member->chat_id = $chat->id;
                    $member->member_id = $plan_member;
                    $member->save();

                    //Add new member to this chat
                    $member = new ChatMember;
                    $member->chat_id = $chat->id;
                    $member->member_id = $user->id;
                    $member->save();
                }
            }
        }

        $datatosend['email'] = $user->email;
        $datatosend['password'] = $plain_password;
        $datatosend['planShow'] = 0;
        try {
            Mail::send('emails.welcome_email', $datatosend, function ($m) use ($user) {
                $m->from('info@driveroadside.com', 'DRIVE | Roadside App');
                $m->to($user->email, $user->name)->subject('Welcome to DRIVE | ROADSIDE');
            });
        }catch (\Exception $e){
            return sendError($e->getMessage(), 400);
        }

        return sendSuccess('Member registered successfully.', $user);
    }

    function get_members(){

        $user_id = Auth::user()->getAuthIdentifier();
        $owner = true;
        if(isset(Auth::user()->getSubscription->stripe_plan)) {
            if (Auth::user()->getSubscription->stripe_plan != env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')) {
                $user = User::find($user_id);
                if ($user->linked_id == null) {
                    $user_id = $user->id;
                    $owner = true;
                } else {
                    $user_id = $user->linked_id;
                    $owner = false;
                }
            }
        }  else {
            $user = User::find($user_id);
            if ($user->linked_id == null) {
                return sendError('Upgrade your membership to have this feature.', 401);
            } else {
                $user_id = $user->linked_id;
                $owner = false;
            }
        }

        $record = User::where('linked_id', $user_id)->get();

//        if(count($record) > 0){
            foreach ($record as $member) {
                if($member->id != Auth::user()->id) {

                    $single_auth_chats = Chat::where('type', 'single')->with('members')
                        ->whereHas('members', function ($query) use ($member) {
                            $query->where('member_id', Auth::user()->id);
                        })->get();

                    foreach ($single_auth_chats as $single_auth_chat) {
                        $single_chat = ChatMember::where('chat_id', $single_auth_chat->id)
                            ->where('member_id', $member->id)->first();
                        if ($single_chat) {
                            $member->single_chat_id = $single_chat->chat_id;
                            break;
                        } else {
                            $member->single_chat_id = null;
                        }
                    }

                    $group_chat = Chat::where('admin_id', $member->linked_id)->where('type', 'group')
                        ->whereHas('members', function ($query) use ($member) {
                            $query->where('member_id', $member->id);
                        })->first();
                    $member->group_chat_id = $group_chat ? $group_chat->id : null;
                }
            }

            $data['family_members'] = $record;
            $data['plan_owner'] = $owner;
            if($owner){
                $data['plan_owner_id'] = Auth::id();
                $owner_object = User::where('id', Auth::id())->first();
                $owner_group_chat = Chat::where('admin_id',Auth::id())->where('type', 'group')->first();
                $owner_object->group_chat_id = $owner_group_chat ? $owner_group_chat->id : null;
                $data['plan_owner_user'] = $owner_object;
            } else {
                $owner_instance = User::where('id', $user_id)->first();

                $single_auth_chats = Chat::where('type', 'single')->with('members')
                    ->whereHas('members', function ($query) {
                        $query->where('member_id', Auth::user()->id);
                    })->get();

                foreach ($single_auth_chats as $single_auth_chat) {
                    $single_chat = ChatMember::where('chat_id', $single_auth_chat->id)
                        ->where('member_id', $owner_instance->id)->first();
                    if ($single_chat) {
                        $owner_instance->single_chat_id = $single_chat->chat_id;
                        break;
                    } else {
                        $owner_instance->single_chat_id = null;
                    }
                }
                $owner_group_chat = Chat::where('admin_id',$user_id)->where('type', 'group')->first();
                $owner_instance->group_chat_id = $owner_group_chat ? $owner_group_chat->id : null;

                $data['plan_owner_id'] = $user_id;
                $data['plan_owner_user'] = $owner_instance;
            }

            return sendSuccess('Family Members', $data);
//        } else {
//            return sendError('No record found', 405);
//        }

    }

    function get_member_details(Request $request){
        if(Auth::user()->linked_id != null){

        }
        else if(!isset(Auth::user()->getSubscription )){
            return sendError('Upgrade your membership to have this feature.', 401);
        }
//
//        if(Auth::user()->linked_id !== null){
//
//        }else if(Auth::user()->getSubscription->stripe_plan != env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')){
//            return sendError('Upgrade your membership to have this feature.', 401);
//        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'member_id' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 401);
        }
        $record = User::where('id', $request->member_id)->first()->toArray();

        $services = Service::where('used_by', $record['id'])->get();
        $user_services = [];
        foreach ($services as $one){
            $user_services[] = $one->service_details();
        }
        $record['used_services'] = $user_services;
        if(isset($record)){
            $data['member_details'] = $record;
            return sendSuccess('Family Member Details', $data);
        } else {
            return sendError('Invalid Request, no record found', 405);
        }
    }

    function remove_member(Request $request){
        if(Auth::user()->linked_id != null){

        }
        else if(!isset(Auth::user()->getSubscription )){
            return sendError('Upgrade your membership to have this feature.', 401);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 401);
        }

        Service::where('used_by', $request->user_id)->update(['used_by' => null]);
        User::where('id', $request->user_id)->where('linked_id', Auth::user()->getAuthIdentifier())->delete();
        return sendSuccess('Member removed successfully.', null);
    }

    public function addCard(Request $request){
        $validator = Validator::make($request->all(), [
            'stripe_id' => 'required',
            'card_brand' => 'required',
            'card_last_four' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 401);
        }

        $user = Auth::user();

        $stripe = \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $pm = \Stripe\PaymentMethod::retrieve(
            $request->stripe_id
        );
        $pm->attach(['customer' => $user->stripe_id]);

        $user->card_last_four = $request->card_last_four;
        $user->card_brand = $request->card_brand;
        $user->save();

        \Stripe\Customer::update(
            $user->stripe_id,
            [ 'invoice_settings' => [ 'default_payment_method' => $request->stripe_id ] ]
        );

        $pms = \Stripe\PaymentMethod::all([
            'customer' => $user->stripe_id,
            'type' => 'card',
        ]);
        foreach($pms->data as $one){
            if($one->id == $request->stripe_id){
                continue;
            }else{
                $pm = \Stripe\PaymentMethod::retrieve(
                    $one->id
                );
                $pm->detach();
            }
        };


        return sendSuccess('Card updated successfully.', $user);
    }

    public function on_off_tracking(Request $request){
//        if(Auth::user()->linked_id !== null){
//        }else if(Auth::user()->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')){
//        }else{
//            return sendError('Upgrade your membership to have this feature.', 401);
//        }
        $validator = Validator::make($request->all(), [
            'tracking' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ], 200);
        }
        $user = User::where('id', Auth::id())->first();
        if(isset($user)){
            $user->tracking = $request->tracking;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Tracking status updated successfully',
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Invalid request, no user found.',
                'data' => []
            ], 200);
        }
    }

    public function update_user_location(Request $request){
//        if(Auth::user()->linked_id !== null){
//        }else if(Auth::user()->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')){
//        }else{
//            return sendError('Upgrade your membership to have this feature.', 401);
//        }
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ], 200);
        }
        $user = User::where('id', Auth::id())->first();
        if(isset($user)){
            $user->lat = $request->lat;
            $user->lng = $request->lng;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'User location updated successfully',
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Invalid request, no user found.',
                'data' => []
            ], 200);
        }
    }


    public function get_updated_location(Request $request){
        if(Auth::user()->linked_id !== null){
        }else if(Auth::user()->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')){
        }else{
            return sendError('Upgrade your membership to have this feature.', 401);
        }

        $user = User::where('id', Auth::id())->first();
        if(isset($user)){

            return response()->json([
                'status' => true,
                'message' => 'User updated location',
                'data' => $user
            ], 200);
        }else{
            return sendError( 'Invalid request, no user found.', 401);
        }
    }



    //API's
    function get_all_users(){
        $users = User::all();
        if (count($users) > 0) {
            return response()->json([
                'status' => true,
                'message' => 'Users fetched successfully.',
                'data' => $users
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'No user found.',
                'data' => []
            ], 200);
        }
    }

    function get_user_detail(Request $request) {
        $user_details = User::with('getSubscription')->where('email', $request->email)->first();
        if (isset($user_details)) {
            return response()->json([
                'status' => true,
                'message' => 'User details fetched successfully.',
                'data' => $user_details
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'No user details found.',
                'data' => null
            ], 200);
        }
    }

    function update_user_details(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'name' => 'required',
            'contact_number' => 'required',
//            'address' => 'required',
//            'zipcode' => 'required',
//            'state' => 'required',
//            'country' => 'required',
//            'city' => 'required',
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ], 200);
        }

        $user = User::where('email', $request->email)->first();
        if(isset($user)){
            $user->name = $request->name;
            $user->contact_number = $request->contact_number;
            $user->address = $request->address;
            $user->zipcode = $request->zipcode;
            $user->state = $request->state;
            $user->country = $request->country;
            $user->city = $request->city;

            if($request->has('image')){
                $path = public_path() . '/svg/';
                File::delete($path . $user->avatar);
                $image = $request->file('image');
                $filename = str_random(10) . '.' . $image->getClientOriginalExtension();
                $image->move($path, $filename);
                $user->avatar = $filename;
            }
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'User profile updated successfully',
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Invalid request, no user found.',
                'data' => []
            ], 200);
        }
    }

    function get_used_services(Request $request) {
        $user = User::where('email', $request->email)->first();
        if(isset($user)){
            $query = Service::query();
            $query->when('used_by' == null, function ($q) use($user){
                return $q->where('user_id', $user->id);
            });
            $query->when('used_by' != null, function ($q) use($user){
                return $q->where('used_by', $user->id);
            });

            $used_services = $query->get();

            if (count($used_services)) {
                return response()->json([
                    'status' => true,
                    'message' => 'User used services fetched successfully.',
                    'data' => $used_services
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'No used services found.',
                    'data' => []
                ], 200);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Invalid request, no user found.',
                'data' => []
            ], 200);
        }
    }

//    //add Payment method
//    public function add_payment_method(Request $request)
//    {
//        $user = Auth::user();
//        try {
//            $stripe = \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
//            $token =  \Stripe\PaymentMethod::create([
//                'type' => 'card',
//                'card' => [
//                    'number' => $request->cardnumber,
//                    'exp_month' => $request->month,
//                    'exp_year' => $request->year,
//                    'cvc' => $request->cvc,
//                ],
//                'billing_details' => [
//                    'email' => $user->email,
//                    'name' =>  $user->name,
//                    'phone' => $user->contact_number,
//                    'address' =>[
//                        "line1" => $user->address,
//                        "postal_code" => $user->zipcode,
//                        "state" => $user->state,
//                        "city" => $user->city,
//                        "country" => $user->country
//                    ]
//                ],
//            ]);
//            $customer = \Stripe\Customer::create([
//                'description' => $user->email.' added as customer',
//                'payment_method' => $token->id,
//                'email' => $user->email,
//                'name' =>  $user->name,
//                'phone' => $user->contact_number,
//                'invoice_settings' => [
//                    'default_payment_method' => $token->id
//                ],
//            ]);
//            $user->card_last_four = $token->card->last4;
//            $user->card_brand = $token->card->brand;
//            $user->stripe_id = $customer->id;
//            $user->save();
//
//            return sendSuccess('Payment Method added successfully.', $user);
//
//        } catch (\Stripe\Error\Card $e) {
//            return sendError($e->getMessage(), 401);
//        } catch (\Stripe\Error\RateLimit $e) {
//            return sendError($e->getMessage(), 401);
//        } catch (\Stripe\Error\InvalidRequest $e) {
//            return sendError($e->getMessage(), 401);
//        } catch (\Stripe\Error\Authentication $e) {
//            return sendError($e->getMessage(), 401);
//        } catch (\Stripe\Error\ApiConnection $e) {
//            return sendError($e->getMessage(), 401);
//        } catch (\Stripe\Error\Base $e) {
//            return sendError($e->getMessage(), 401);
//        } catch (Exception $e) {
//            return sendError($e->getMessage(), 401);
//        }
//    }
//
//    public function update_payment_method(Request $request)
//    {
//        $user = Auth::user();
//        try {
//            $stripe = \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
//            $token =  \Stripe\PaymentMethod::create([
//                'type' => 'card',
//                'card' => [
//                    'number' => $request->cardnumber,
//                    'exp_month' => $request->month,
//                    'exp_year' => $request->year,
//                    'cvc' => $request->cvc,
//                ],
//                'billing_details' => [
//                    'email' => $user->email,
//                    'name' =>  $user->name,
//                    'phone' => $user->contact_number,
//                    'address' =>[
//                        "line1" => $user->address,
//                        "postal_code" => $user->zipcode,
//                        "state" => $user->state,
//                        "city" => $user->city,
//                        "country" => $user->country
//                    ]
//                ],
//            ]);
//            $pm = \Stripe\PaymentMethod::retrieve(
//                $token->id
//            );
//            $pm->attach(['customer' => $user->stripe_id]);
//
//            $user->card_last_four = $token->card->last4;
//            $user->card_brand = $token->card->brand;
//            $user->save();
//
//            \Stripe\Customer::update(
//                $user->stripe_id,
//                [ 'invoice_settings' => [ 'default_payment_method' => $token->id ] ]
//            );
//
//            $pms = \Stripe\PaymentMethod::all([
//                'customer' => $user->stripe_id,
//                'type' => 'card',
//            ]);
//            foreach($pms->data as $one){
//                if($one->id == $token->id){
//                    continue;
//                }else{
//                    $pm = \Stripe\PaymentMethod::retrieve(
//                        $one->id
//                    );
//                    $pm->detach();
//                }
//            };
//            return sendSuccess('Payment Method updated successfully.', $user);
//
//        } catch (\Stripe\Error\Card $e) {
//            return sendError($e->getMessage(), 401);
//        } catch (\Stripe\Error\RateLimit $e) {
//            return sendError($e->getMessage(), 401);
//        } catch (\Stripe\Error\InvalidRequest $e) {
//            return sendError($e->getMessage(), 401);
//        } catch (\Stripe\Error\Authentication $e) {
//            return sendError($e->getMessage(), 401);
//        } catch (\Stripe\Error\ApiConnection $e) {
//            return sendError($e->getMessage(), 401);
//        } catch (\Stripe\Error\Base $e) {
//            return sendError($e->getMessage(), 401);
//        } catch (Exception $e) {
//            return sendError($e->getMessage(), 401);
//        }
//    }


    public function send_alert_notification(Request $request){
        $validator = Validator::make($request->all(), [
            'family_members' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ], 200);
        }
        $familymembers = explode(",", $request->family_members);
        foreach ($familymembers as $familymember){
            $notification = new NotificationsController();
            $datatosend['family_id'] = $familymember;
            $name = Auth::user()->name;
            $notification->sendPushNotification("Hello, your family member $name shared it's location.", $familymember, $datatosend);
        }
        return sendSuccess('Location shared successfully!.', $familymembers);

    }
}
