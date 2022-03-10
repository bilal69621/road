<?php

namespace App\Http\Controllers;

use App\Category;
use App\Chat;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Subscription;
use App\Payment;
use Laravel\Cashier;
use Carbon\Carbon;
use App\Admin;
use App\coupon;
use \Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Helpers\ArrayHelper;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;
use Session;
use Redirect;
use App\cancelationReasons;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Stripe\Stripe;
use Response;
use App\Jobs;
use App\blog;

//use Twilio\Rest\Client;


class AdminController extends Controller
{

    public function dashboard()
    {

        $this->data['user_count'] = User::count();
        $this->data['active_subscription'] = Subscription::all()->where('status', 1)->count();
        $this->data['inactive_subscription'] = Subscription::all()->where('status', 0)->count();
        $this->data['renew_counter'] = Subscription::all()->sum('counter');
        //$this->data['renew_subscription'] = Payment::all()->count();
//        $sub_total = Subscription::all()->sum('amount');
        $this->data['toal_revenue'] = Payment::all()->sum('amount') / 100; //Total Revenue
        $this->data['pay_total_year'] = Payment::whereDate('created_at', '>=', Carbon::today()->subYear(1))->sum('amount') / 100; //Last Year Revenue
        $this->data['pay_total_quater'] = Payment::whereDate('created_at', '>=', Carbon::today()->subMonth(3))->sum('amount') / 100; //Last Quarter Revenue
        $this->data['pay_total_month'] = Payment::whereDate('created_at', '>=', Carbon::today()->subMonth(1))->sum('amount') / 100;//Last Month Days Revenue
        $this->data['pay_total_week'] = Payment::whereDate('created_at', '>=', Carbon::today()->subDays(7))->sum('amount') / 100;//Last Seven Days Revenue
        $this->data['pay_total_day'] = Payment::whereDate('created_at', Carbon::today())->sum('amount') / 100; //Today Revenue
        $mygetdate = \Carbon\Carbon::today()->subYear(1);
//        dd($this->data['pay_total_year']);
        $active_sub = Subscription::whereDate('created_at', '>=', Carbon::today()->subYear(1))->count();
        $inactive_sub = Subscription::whereDate('created_at', '>=', Carbon::today()->subYear(1))->where('status', 0)->count();
        // $this->data['churn_date'] =  round((($active_sub-$inactive_sub)/$active_sub)*100, 2);
        $plan_49 = Subscription::all()->where('status', 1)->where('stripe_plan', env('STRIPE_PLAN_6_MONTHS'))->count();
        $plan_89 = Subscription::all()->where('status', 1)->where('stripe_plan', env('STRIPE_PLAN_1_YEAR'))->count();
        $plan_129 = Subscription::all()->where('status', 1)->where('stripe_plan', env('STRIPE_PLAN_1_YEAR_PLUS'))->count();
        $this->data['life_time_value'] = (($plan_49 * 49 * 2 * 10) + ($plan_89 * 89 * 10) + ($plan_129 * 129 * 10));
//        dd($life_time_value);
        $this->data['tab'] = 'dashboard';

//        whereDate('created_at', Carbon::now()->subDays(7))
//        whereMonth('created_at', '=', Carbon::now()->subMonth()->month);

        return view('admin.dashboard', $this->data);
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, ['email' => 'required', 'password' => 'required']);
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('dashboard');
        } else {
            return redirect()->back()->with('error', 'Invalid Email or Password');
        }
    }

    public function logout()
    {
        if (Auth::guard('admin')->logout()) {
            return redirect('/');
        } else {
            return redirect()->back()->with('error', 'Not Logout');
        }
    }

    public function testCreateCardToken()
    {
//        \Stripe\Stripe::setApiKey("sk_test_5U2DWCQfgd0issmQbyH3MSOi");
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $token = \Stripe\Token::create([
            'card' => [
                'number' => '4242 4242 4242 4242',
                'exp_month' => 6,
                'exp_year' => 2020,
                'cvc' => '314'
            ]
        ]);
        return $token;
    }

    public function createSubscription(Request $request)
    {
        $id = $request->id;
        $token = $request->token;
        $user = User::find($id);

        if ($user->newSubscription('RoadSide', env('STRIPE_PLAN_1_YEAR_PLUS'))->create($token)) {

            return redirect()->back()->with('success', 'Subscription Create Successfully!');

        } else {
            return redirect()->back()->with('error', 'Error while creating subsciption');
        }


    }

//    public function getSubscriptionPlan(){
//        \Stripe\Stripe::setApiKey("sk_test_5U2DWCQfgd0issmQbyH3MSOi");
//
//        $signle_plan['subscription'] = \Stripe\Subscription::retrieve('sub_FEub2ZBGHpBTxS');
//        return view('admin.dashboard',$signle_plan);
//    }

//    public function allSubscription(){
//        \Stripe\Stripe::setApiKey("sk_test_5U2DWCQfgd0issmQbyH3MSOi");
//
////        $signle_plan['subscriptions'] = \Stripe\Subscription::all(['limit' => 3]);
//        $signle_plan['subscriptions'] = \Stripe\Subscription::all();
//        return view('admin.dashboard',$signle_plan);
//
//    }

    public function cancelSubscription($subscription_id)
    {

//            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $sub = \Stripe\Subscription::retrieve($subscription_id);
        $sub->cancel();

        return redirect()->back()->with('success', 'Subscription cancel successfully!!!');

    }

    public function getusers()
    {
        $this->data['tab'] = 'users';

        $this->data['title'] = 'Users';

//        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $this->data['subscriptions'] = \Stripe\Subscription::all();
        $this->data['users'] = User::with('getSubscription', 'getJob')->get();
        return view('admin.users', $this->data);
    }

    // function for getting acive jobs
    public function getactiveJobs()
    {
        $this->data['tab'] = 'active_jobs';
        $this->data['title'] = 'Active Jobs';

        $activeJobs = Jobs::where([
            ['status', '!=', 'completed'],
            ['status', '!=', 'deleted'],
            ['status', '!=', 'canceled']
        ])->with('user')->get();
        $this->data['jobs'] = $activeJobs;
        return view('admin.activeJobs', $this->data);
    }

    public function getSubscriptions()
    {
        /*
         * Old One

        $this->data['tab']= 'subscriptions';
        $this->data['title']= 'Subscriptions';
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
//        \Stripe\Stripe::setApiKey('sk_test_5U2DWCQfgd0issmQbyH3MSOi');
        $this->data['subscriptions'] = \Stripe\Subscription::all();
//        $users = $this->data['subscriptions']->with('getUser')->get();
//        dd($this->data['subscriptions']);

        return view('admin.subscriptions',$this->data);
        */

        $data['tab'] = 'subscriptions';
        $data['title'] = 'Subscriptions';
//        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
//        $data['subscriptions'] = \Stripe\Subscription::all();
        $data['subscriptions'] = Subscription::with('getUser.getPaymnet')->orderBy('id', 'DESC')->get();
        return view('admin.subscriptions', $data);
    }

// function for cancel Reasons
    public function cancelReasons()
    {
        $data['Reasons'] = cancelationReasons::with('user')->orderBy('id', 'DESC')->get();
        $data['tab'] = 'Cancel Reasons';
        $data['title'] = 'cancel_reasons';
        return view('admin.cancelReasons', $data);
    }


//    Edit Admin Profile View
    function editProfileView()
    {

        $data['tab'] = 'edit_profile';
        $data['title'] = 'Edit My Profile';
        $id = Auth::guard('admin')->user()->id;
        $data['detail'] = Admin::find($id)->first();
        return view('admin.edit-admin-profile', $data);
    }

//    Save Admin Profile Data
    function editProfileData(Request $request)
    {
        $request->validate([
            'full_name' => 'required|min:1|max:191',
            'email' => 'required|email|max:191',
            'profile_img' => 'image|mimes:jpeg,jpg,bmp,png,gif',
        ]);
//        dd($request->full_name);
        //Get Admin ID
        $id = Auth::guard('admin')->user()->id;
        //Check Image Exist
        $img_check = $request->hasFile('profile_img');
        //Fetch Admin From DB
        $admin = Admin::find($id)->first();
        //Set values against fields
        $admin->full_name = $request->full_name;
        $admin->email = $request->email;
        //Fetch Image
        $old_photo = 'public/images/' . $admin->profile_pic;
        //If New Image Added than delete old image and insert new image to DB and folder
        if ($img_check) {

            $image = $request->file('profile_img');
            $path = 'public/images/admin/profile_pic/';
            $random = substr(md5(mt_rand()), 0, 20);
            $filename = $random . '.' . $image->getClientOriginalExtension();
            $image->move($path, $filename);
            $final_path = '/admin/profile_pic/' . $filename;
            $admin->profile_pic = $final_path;
            //Deleting image from folder
            if (File::exists($old_photo)) {
                File::delete($old_photo);
            }

        }
        $admin->save();
        Session::flash('success', 'Updated successfully');
        return Redirect::to(URL::previous());


    }

//    Chnage Admin Password
    function changePassword(Request $request)
    {

        // Setup the validator
        $message = ['password.required' => 'The new password field is required.'];
        $rules = array('current_password' => 'required', 'password' => 'min:5|required|confirmed|max:191', 'password_confirmation' => 'required|max:191',);
        $validator = Validator::make(Input::all(), $rules, $message);
        // Validate the input and return correct response
        if ($validator->fails()) {
            $errors = $validator->getMessageBag()->toArray();
            return Response::json(array(
                'status' => false,
                'message' => $errors,
                'from' => 'validator'

            ), 400); // 400 being the HTTP code for an invalid request.
        }
        $password = Auth::guard('admin')->user()->password;
        if (Hash::check($request['current_password'], $password)) {

            $id = Auth::guard('admin')->user()->id;
            $admin = Admin::find($id)->first();
            $admin->password = bcrypt($request->password);

            $admin->save();
//            Session::flash('success', 'Password Updated successfully');
            return Response::json(array('status' => true, 'message' => 'Password Updated successfully'), 200);

        } else {
            //Session::flash('error', 'Invalid Old Password');
            //return Redirect::to(URL::previous());
            return Response::json(array('success' => false, 'message' => 'Invalid Old Password', 'from' => 'invalid'), 400);

        }
    }

    public function hubSpot($name, $email, $address, $state, $country, $plan)
    {


        $data2 = array(
            'fields' =>
                array(
                    0 =>
                        array(
                            'name' => 'email',
                            'value' => $email,
                        ),
                    1 => array(
                        'name' => 'firstname',
                        'value' => $name
                    ),
                    2 => array(
                        'name' => 'plan',
                        'value' => $plan
                    ),

                    3 => array(
                        'name' => 'state',
                        'value' => $state
                    ),
                    4 => array(
                        'name' => 'country',
                        'value' => $country
                    ),
                    5 => array(
                        'name' => 'address',
                        'value' => $address
                    ),
                    6 => array(
                        'name' => 'phone',
                        'value' => '1234654888'
                    ),

                ),

            'legalConsentOptions' =>
                array(
                    'consent' =>
                        array(
                            'consentToProcess' => true,
                            'text' => 'I agree to allow Example Company to store and process my personal data.',
                            'communications' =>
                                array(
                                    0 =>
                                        array(
                                            'value' => true,
                                            'subscriptionTypeId' => 999,
                                            'text' => 'I agree to receive marketing communications from Example Company.',
                                        ),
                                ),
                        ),
                ),
        );
        $post_json = json_encode($data2);
        $endpoint = 'https://api.hsforms.com/submissions/v3/integration/submit/' . env('HUBSPOT_PORTAL_ID') . '/' . env('HUBSPOT_FORM_ID');
        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        @curl_setopt($ch, CURLOPT_URL, $endpoint);
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        $response = curl_exec($ch);

        curl_close($ch);

        return "Contact Created!";
    }

    function registerProfile()
    {
        $data['tab'] = 'register_profile';
        $data['title'] = 'Drive Roadside | Registration Page';
        $data['description'] = 'Sign up today for our roadside assistance monthly plan for just $9.99. Our roadside assistance technicians are available 24/7 in all states';
        return view('users.register_view', $data);
    }

    function register(Request $request)
    {

        $this->hubSpot($request->name, $request->email, $request->address, $request->state, $request->country, $request->plan);
        if (isset($request->coupon)) {
            $coupon = $request->coupon;
            $coupon = coupon::where('coupon', $coupon)->first();
            if ($coupon == Null) {
                $response = array(
                    'message' => 'Invalid Coupon',
                );
                return $this->sendError($response);
            }
            $expirey = $coupon->valid;
            $current = strtotime(date("Y-m-d H:i:s"));
            if ($expirey < $current) {
                $response = array(
                    'message' => 'Expire Coupon',
                );
                return $this->sendError($response);
            }
            if ($coupon == Null) {
                $response = array(
                    'message' => 'Invalid Coupon',
                );
                return $this->sendError($response);
            }
            try {
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                $coupon223 = \Stripe\Coupon::retrieve($request->coupon, []);

            } catch (\Stripe\Error\RateLimit $e) {
                $response = array(
                    'message' => 'Invalid Coupon',
                );
                return $this->sendError($response);
            } catch (\Stripe\Error\InvalidRequest $e) {
                $response = array(
                    'message' => $e->getMessage(),
                );
                return $this->sendError($response);

            }
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
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $data['title'] = 'Register';

        $input = $request->all();
        $token = bcrypt(str_random(15));
        $input['remember_token'] = $token;
//           $input['address'] = $input['address'];
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $user->session_token = $user->createToken('RoadSide')->accessToken;
        $user->save();

        // sending message to newly register

//            $sid = "ACbf7aaf5f9e4ed09e510b2c3dab114e40";
//            $token = "dfd1bd87a3e799fa3d8a38331404bf84";
//            $phone_number = $request->contact_number;
//            $message_body = 'Welcome to Drive Roadside. Here is the link to get our App. https://www.driveroadside.com/';
//
//            try {
//                $client = new Client($sid, $token);
//                $message = $client->messages->create(
//                        $phone_number, array(
//                            "from" => "+12054987048",
//                            "body" => $message_body,
//                            "provideFeedback" => True
//                        )
//                );
//            } catch (\Twilio\Exceptions\TwilioException $e) {
//                return redirect()->back()->with('error', $e->getMessage());
//            }

        // sending email to new user
//
        $datatosend['url'] = asset('/verify?token=') . $token;
        $datatosend['name'] = $user->name;

        $datatosend['email'] = $user->email;
        $datatosend['password'] = $request['password'];


        // Mail Gun
//           Mail::send('emails.register',$datatosend, function ($m) use ($user) {
//            $m->from('support@roadside.com', 'Roadside App');
//
//            $m->to($user->email, $user->name)->subject('Welcome To RoadSide');
//        });

//           $mg = Mailgun::create('17affd9a7386e71ceea3c622d08b13e8-bbbc8336-ada14c5b');
//
//           $mg->messages()->send('sandboxb53cab8671c34b5180a0ba54033f509d.mailgun.org', [
//  'from'    => 'Excited User <support@roadside.com>',
//  'to'      => 'Baz <codingpixel.test6@gmail.com>',
//  'subject' => 'Hello',
//  'text'    => 'Testing some Mailgun awesomness!'
//]);


        $end_date = '';
        $data['plan'] = $request->plan;
        if ($request->plan == '10MilesMonthlyPlan') {
            $planToken = env('STRIPE_PLAN_MONTHLY_PLAN');
            $miles = 10;
            $counter = 4;
            $end_date = Carbon::now()->addMonths(12)->format('Y-m-d H:i:s');
        } else if ($request->plan == '10MilesPlusYearFamilyPlan') {
            $planToken = env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN');
            $miles = 10;
            $counter = 4;
            $end_date = Carbon::now()->addMonths(12)->format('Y-m-d H:i:s');
        } else if ($request->plan == '10MilesPlusYear') {
            $planToken = env('STRIPE_PLAN_1_YEAR_PLUS');
            $miles = 10;
            $counter = 4;
            $end_date = Carbon::now()->addMonths(12)->format('Y-m-d H:i:s');
        } else if ($request->plan == '10MilesYear') {
            $planToken = env('STRIPE_PLAN_1_YEAR');
            $miles = 10;
            $counter = 2;
            $end_date = Carbon::now()->addMonths(12)->format('Y-m-d H:i:s');
        } else if ($request->plan == '6Months') {
            $planToken = env('STRIPE_PLAN_6_MONTHS');
            $miles = 10;
            $counter = 1;

            $end_date = Carbon::now()->addMonths(6)->format('Y-m-d H:i:s');
        } else {
            $data['plan'] = '10MilesYear';
            $planToken = env('STRIPE_PLAN_1_YEAR');
            $miles = 10;
            $counter = 2;

            $end_date = Carbon::now()->addMonths(6)->format('Y-m-d H:i:s');
        }

        $datatosend['plan'] = $data['plan'];
        $datatosend['endDate'] = $end_date;
        $datatosend['planShow'] = 1;
//        try {
//            Mail::send('emails.welcome_email', $datatosend, function ($m) use ($user) {
//                $m->from('support@roadside.com', 'DRIVE | Roadside App');
//                $m->to($user->email, $user->name)->subject('Welcome to DRIVE | ROADSIDE');
//            });
//        }catch (\Exception $e){
//            return $this->sendError($e->getMessage());
//        }
//        try {
//
//           \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
//            $token=  \Stripe\Token::create([
//                 'card' => [
//                     'number' => $request->cardnumber,
//                     'exp_month' => $request->month,
//                     'exp_year' => $request->year,
//                     'cvc' => $request->cvc
//                 ]
//             ]);
//        } catch (\Stripe\Error\Card $e) {
//            return $this->sendError($e->getMessage());
//        } catch (\Stripe\Error\RateLimit $e) {
//            return $this->sendError($e->getMessage());
//        } catch (\Stripe\Error\InvalidRequest $e) {
//            return $this->sendError($e->getMessage());
//        } catch (\Stripe\Error\Authentication $e) {
//            return $this->sendError($e->getMessage());
//        } catch (\Stripe\Error\ApiConnection $e) {
//            return $this->sendError($e->getMessage());
//        } catch (\Stripe\Error\Base $e) {
//            return $this->sendError($e->getMessage());
//        } catch (Exception $e) {
//            return $this->sendError($e->getMessage());
//        }
//
//           if($get = $user->newSubscription('RoadSide', $planToken)->create($token->id)){
        try {

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
//            $token=  \Stripe\Token::create([
//                 'card' => [
//                     'number' => $request->cardnumber,
//                     'exp_month' => $request->month,
//                     'exp_year' => $request->year,
//                     'cvc' => $request->cvc,
//                     'name' => $user->name,
//                     'address_line1' => $user->address,
//                     'address_city' => $user->city,
//                     'address_state' => $user->state,
//                     'address_zip' => $user->zipcode,
//                     'address_country'=> $user->country,
//		     'email'=> $user->email
//                 ]
//             ]);
            $token = \Stripe\PaymentMethod::create([
                'type' => 'card',
                'card' => [
                    'number' => $request->cardnumber,
                    'exp_month' => $request->month,
                    'exp_year' => $request->year,
                    'cvc' => $request->cvc,
                ],
                'billing_details' => [
                    'email' => $user->email,
                    'name' => $user->name,
                    'phone' => $user->contact_number,
                    // 'line1' => $user->address,
                    'address' => [
                        "line1" => $user->address,
//                                "line2" => null,
                        "postal_code" => $user->zipcode,
                        "state" => $user->state,
                        "city" => $user->city,
                        "country" => $user->country
                    ]
                ],
            ]);
            $customer = \Stripe\Customer::create([
                'description' => $user->email . ' added as customer',
                'payment_method' => $token->id,
                'email' => $user->email,
                'name' => $user->name,
                'phone' => $user->contact_number,
                'invoice_settings' => [
                    'default_payment_method' => $token->id
                ],
//                'shipping' => [
//                              'address' => $user->address,
//                              'name' =>  $user->name,
//                              'phone' => $user->contact_number
//                            ],
            ]);

            $user->card_last_four = $token->card->last4;
            $user->card_brand = $token->card->brand;
//            dd($customer->id);
            $user->stripe_id = $customer->id;
            $user->save();
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
        if (isset($coupon223)) {
            if ($get = \Stripe\Subscription::create([
                "customer" => $user->stripe_id,
                "items" => [
                    [
                        "plan" => $planToken,
                    ],
                ],
                "coupon" => $coupon223->id,

            ])) {
                $stripe_details = \Stripe\Subscription::retrieve($get->id);
                $subscription = Subscription::create([
                    'name' => 'RoadSide',
                    'user_id' => $user->id,
                    'counter' => $counter,
                    'stripe_plan' => $get->id,
                    'stripe_id' => $user->stripe_id,
                    'quantity' => 1,
                    'total_miles' => 10
                ]);

                $subscription->status = 1;
                $subscription->total_miles = $miles;
                $subscription->counter = $counter;
                $timestamp = date('Y-m-d H:i:s', $stripe_details->current_period_end);
                $subscription->ends_at = $end_date;

                $subscription->save();

                if ($stripe_details->plan->amount == null) {
                    $stripe_details->plan->amount = 999;
                }

                $data['amount'] = $plan_amount = $stripe_details->plan->amount;
                $payement = new Payment();
                $payement->user_id = $user->id;
                $payement->amount = $plan_amount;
                $payement->charge_id = $user->stripe_id;
                $payement->save();

                $group_chat = new Chat;
                $group_chat->admin_id = $user->id;
                $group_chat->type = 'group';
                $group_chat->save();
                $user->group_chat_id = $group_chat ? $group_chat->id : null;

                $plan_percentage = (($plan_amount / 100) * 5);
                $data['stripe_detail'] = $get;
                $data['success'] = 'Thank you for Subscribing ' . $data['plan'] . ' Membership.';
                $data['user'] = $user;
                $data['amount'] = $plan_percentage;

                return view('users.login', $data);

            } else {
                User::where('id', $user->id)->delete();
                return Response::json(array('success' => false, 'message' => 'Cant create Stripe Token'), 200);
            }
        }

        if ($get = $user->newSubscription('RoadSide', $planToken)->create()) {
            $stripe_details = \Stripe\Subscription::retrieve($get->stripe_id);

            $subscription = Subscription::where('stripe_id', $get->stripe_id)->first();
            $subscription->status = 1;
            $subscription->total_miles = $miles;
            $subscription->counter = $counter;
            $timestamp = date('Y-m-d H:i:s', $stripe_details->current_period_end);
            $subscription->ends_at = $end_date;

            $subscription->save();

            if ($stripe_details->plan->amount == null) {
                $stripe_details->plan->amount = 999;
            }
            $data['amount'] = $plan_amount = $stripe_details->plan->amount;
            $payement = new Payment();
            $payement->user_id = $user->id;
            $payement->amount = $plan_amount;
            $payement->charge_id = $get->stripe_id;
            $payement->save();

            $group_chat = new Chat;
            $group_chat->admin_id = $user->id;
            $group_chat->type = 'group';
            $group_chat->save();
            $user->group_chat_id = $group_chat ? $group_chat->id : null;

            $plan_percentage = (($plan_amount / 100) * 5);
            $data['stripe_detail'] = $get;
            $data['success'] = 'Thank you for Subscribing ' . $data['plan'] . ' Membership.';
            $data['user'] = $user;
            $data['amount'] = $plan_percentage;

            return view('users.login', $data);
            // return redirect('user_login')->with('success','Thank you for Subscribing '.$data['plan'].' Membership.');

        } else {
            User::where('id', $user->id)->delete();
            return Response::json(array('success' => false, 'message' => 'Cant create Stripe Token'), 200);
        }

    }


    public function sendError($error_message, $code = 400)
    {
        return redirect()->back()->withErrors($error_message);
        // return Response::json(array('status' => $code, 'errorMessage' => $error_message), $code)->setStatusCode($code, $error_message);
    }

    public function testDateCompare()
    {
        $today = Carbon::now()->format('Y-m-d H:i:s');
        $subs = Subscription::whereDate('ends_at', '>', $today)->get();
//        dd($subs);
//        echo '<pre>';
        return $subs;

    }


    public function verifyUser(Request $request)
    {
//        dd($request);
        $user = User::where('remember_token', $request['token'])->first();
        if (isset($user)) {
            $user->remember_token = null;
            $user->email_verified_at = Carbon::now();
            $user->save();
            Session::put('token_success', 'You have Successfully Verified');
            return redirect('userdashboard');
        } else {
            Session::put('token_error', 'You have already verified or verification link Expired');
            return redirect('userdashboard');
        }
    }


    public function coupon_system()
    {
        $coupons = coupon::all();
        $data = ['coupons' => $coupons];
        $data['tab'] = 'coupon_system';
        $data['title'] = 'coupon_system';
        return view('admin.couponSystem', $data);
    }

    public function blog_system()
    {
        $blogs = blog::all();
        $categories = Category::all();
        $data = ['coupons' => $blogs];
        $data['categories']=$categories;
        $data['tab'] = 'Blog System';
        $data['title'] = 'Blog System';
        return view('admin.blogSystem', $data);
    }

    public function delete_blog($id)
    {
        $blogs = blog::find($id);
        $blogs->delete();
        return redirect()->back()->with('success', 'Blog Deleted Successfully');
    }

    public function createCoupon(Request $re)
    {
        $unixDate = strtotime($re->valid_till_data);
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $couponData = \Stripe\Coupon::create([
            'percent_off' => $re->discount,
            'name' => $re->name,
            'duration' => 'repeating',
            'duration_in_months' => 2,
            'id' => $re->name,
        ]);

        $coupon = new coupon();
        $coupon->coupon = $couponData->id;
        $coupon->valid_till = $re->valid_till_data;
        $coupon->discount = $re->discount;
        $coupon->name = $re->name;
        $coupon->valid = $unixDate;
        $coupon->save();

        return redirect('coupon_system');

    }

    public function creat_blog(Request $re)
    {
        $validator = Validator::make($re->all(), [
            'name' => 'required',
            'p_mdesc' => 'required',
            'image_main' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = implode(', ', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($re->hasFile('image_main')) {
            //  Let's do everything here
            if ($re->file('image_main')->isValid()) {
                $path = public_path() . '/blog/';

                $image = $re->file('image_main');
                $filename = str_random(10) . '.' . $image->getClientOriginalExtension();
                $image->move($path, $filename);
            }
        }
        $blog = new blog();
        $blog->name = $re->name;
        $blog->slug = CreateSlug($re->name);
        $blog->blog = $re->p_mdesc;
        $blog->main_image = $filename;
        $blog->category_id = $re->category_id;
        $blog->save();
        return redirect('blog_system')->with('success', 'Blog Added Successfully');
    }

    public function create_blog_slugs(){
        $blogs = blog::all();
        foreach ($blogs as $blog){
            $blog->slug = CreateSlug($blog->name);
            $blog->save();
        }
        dd('done');
    }

    public function update_blog(Request $re)
    {
        $validator = Validator::make($re->all(), [
            'name' => 'required',
            'p_mdesc' => 'required',

        ]);
        if ($validator->fails()) {
            $errors = implode(', ', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($re->hasFile('image_main')) {
            if ($re->file('image_main')->isValid()) {
                $path = public_path() . '/blog/';
                $image = $re->file('image_main');
                $filename = str_random(10) . '.' . $image->getClientOriginalExtension();
                $image->move($path, $filename);
            }
            $blog = blog::where('id', $re->id)->update([
                'main_image' => $filename,
            ]);
        }
        $blog = blog::where('id', $re->id)->update([
            'name' => $re->name,
            'blog' => $re->p_mdesc,
            'category_id' => $re->category_id,

        ]);
        if ($blog) {
            return redirect('blog_system')->with('success', 'Blog Updated Successfully');
        } else {
            return redirect('blog_system')->with('error', 'Failed To Update');
        }
    }

    public function get_blog(Request $request)
    {
        $data = blog::where('id', $request->blog_id)->first();
        return $data;
    }

    public function store(Request $request)
    {

        if ($request->hasFile('image')) {
            //  Let's do everything here
            if ($request->file('image')->isValid()) {

                $extension = $request->image->extension();
                $request->image->storeAs('/public/blog', $re->name . "_blod." . $extension);
                $url = Storage::url($re->name . "_blod." . $extension);


            }
        }
        abort(500, 'Could not upload image :(');
    }

    public function categories()
    {
        $Categories = Category::all();
        $data = ['coupons' => $Categories];
        $data['tab'] = 'Categories';
        $data['title'] = 'Categories';
        return view('admin.Categories', $data);
    }

    public function creat_category(Request $re)
    {
        $validator = Validator::make($re->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = implode(', ', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $blog = new Category();
        $blog->name = $re->name;
        $blog->save();
        return redirect('categories')->with('success', 'Category Added Successfully');
    }

    public function get_category(Request $request)
    {
        $data = Category::where('id', $request->category_id)->first();
        return $data;
    }

    public function update_category(Request $re)
    {
        $validator = Validator::make($re->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = implode(', ', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $category = Category::where('id', $re->id)->update([
            'name' => $re->name,
        ]);
        if ($category) {
            return redirect('categories')->with('success', 'Category Updated Successfully');
        } else {
            return redirect('categories')->with('error', 'Failed To Update');
        }
    }
    public function delete_category($id)
    {
        $blogs = Category::find($id);
        blog::where('category_id', $id)->update([
            'category_id' => null,
        ]);
        $blogs->delete();
        return redirect()->back()->with('success', 'Category Deleted Successfully');
    }

}
