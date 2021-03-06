<?php

namespace App\Http\Controllers\service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Mail;
use App\AllService;
use App\Imports\CarImport;
use App\User;
use App\Cars;
use App\Jobs;
use Stripe\Stripe;
use App\Subscription;
use App\cancelationReasons;
use App\Service;
use App\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;

class ServiceController extends Controller
{
    public function step1(Request $request){
        $description = 'Request emergency roadside assistance right away. Select what you need roadside assistance with and answer a few questions. Emergency roadside assistance will be on your way in less than two minutes.';
        $title = 'Service Step 1';
        if (!Auth::check()) {
            $user = User::create([
                'name' => 'Guest User',
                'email' => str_random(10) . '@gmail.com',
                'password' => bcrypt('123456'),
                'user_type' => 'guest',
            ]);
            $user->user_type = 'guest';
            $user->save();
            if (Auth::loginUsingId($user->id)) {
                $data['user'] = Auth::user();
            }
        }else{
            $user = Auth::user();
            $userId = Auth::id();
        $userJobs = Jobs::where([
            ['user_id','=',$userId],
            ['status','!=','completed'],
            ['status','!=','deleted'],
            ['status','!=','canceled']
            ])->first();
        if($userJobs)
        {
          return redirect()->route('jobdetail', $userJobs->id);
        }
        }
        return view('service.step_1',compact('title','user','description'));
    }

    public function step12(Request $request){

        $title = 'Service Step 1';
        if (!Auth::check()) {
            $user = User::create([
                'name' => 'Guest User',
                'email' => str_random(10) . '@gmail.com',
                'password' => bcrypt('123456'),
                'user_type' => 'guest',
            ]);
            $user->user_type = 'guest';
            $user->save();
            if (Auth::loginUsingId($user->id)) {
                $data['user'] = Auth::user();
            }
        }else{
            $user = Auth::user();
            $userId = Auth::id();
        $userJobs = Jobs::where([
            ['user_id','=',$userId],
            ['status','!=','completed'],
            ['status','!=','deleted'],
            ['status','!=','canceled']
            ])->first();
        if($userJobs)
        {
          return redirect()->route('jobdetail', $userJobs->id);
        }
        }
        return view('service.step12',compact('title','user'));
    }

    //bilal new functions
    public function directService($id)
    {
        if($id == 'emergency-roadside-assistance')
        {
            return view('service.emergancy');
        }
        if (!Auth::check()) {
            $user = User::create([
                'name' => 'Guest User',
                'email' => str_random(10) . '@gmail.com',
                'password' => bcrypt('123456'),
                'user_type' => 'guest',
            ]);
            $user->user_type = 'guest';
            $user->save();
            if (Auth::loginUsingId($user->id)) {
                $data['user'] = Auth::user();
            }
        }else{
            $user = Auth::user();
            $userId = Auth::id();
            $userJobs = Jobs::where([
                ['user_id','=',$userId],
                ['status','!=','completed'],
                ['status','!=','deleted'],
                ['status','!=','canceled']
            ])->first();
            if($userJobs)
            {
                return redirect()->route('jobdetail', $userJobs->id);
            }

        }
        $type = $id;
        $price = AllService::where('name',$id)->first();
        $checkUserSub = $this->check_before_create_job();
        $checkUserSub = $checkUserSub->getdata();
        if($checkUserSub->error != 0)
        {
            $title = 'Service Step 2';
            return view('service.step_2',compact('type','price','title','user'));
        }else{
            $title = 'Service Step 3';
            return view('service.step_3',compact('type','price','title','user'));
        }

    }
    public function step2(Request $request){
        $user = Auth::user();
        $type = $request->type;
        $price = AllService::where('name',$request->type)->first();
        $checkUserSub = $this->check_before_create_job();
        $checkUserSub = $checkUserSub->getdata();
            if($checkUserSub->error != 0)
            {
            $title = 'Service Step 2';
            return view('service.step_2',compact('type','price','title','user'));
            }else{
              $title = 'Service Step 3';
            return view('service.step_3',compact('type','price','title','user'));
            }
    }
    public function step3(Request $request){
//
        $user = Auth::user();
        $type = $request->type;
        $price = $request->price;
        $title = 'Service Step 3';

        return view('service.step_3',compact('type','price','title','user'));
    }
    public function step4(Request $request){
//
        $user = Auth::user();
        $lat = $request->lat;
        $lng = $request->lng;
        $type = $request->type;
        $price = $request->price;
        $title = 'Service Step 4';
        $check = 0;
        $years = DB::table('all_cars')->distinct('year')->pluck('year');
        if($type == 'tow')
        {
            $check = 1;
        $carRepairs = file_get_contents('https://maps.googleapis.com/maps/api/place/nearbysearch/json?key='.config('app.GOOLE_MAP_KEY').'&rankby=distance&type=car_repair&location='.$lat.','.$lng.'');
        $carRepairs = json_decode($carRepairs,True)['results'];
        $counter = 0;
//
        foreach($carRepairs as $carRepair)
        {
            $phoneNumber = $this->getPhoneNumber($carRepair['place_id']);
            $lat2 = $carRepair['geometry']['location']['lat'];
            $lng2 = $carRepair['geometry']['location']['lng'];

            $milesDistance = $this->distance($lat, $lng, $lat2, $lng2, 'M');

            $carRepairs[$counter]['geometry']['location']['distance'] =round($milesDistance);
            $carRepairs[$counter]['contact'] = $phoneNumber;
            $counter++;
        }

        return view('service.step_4',compact('type','price','lat','lng','title','user','carRepairs','check'));
        }else{
            return view('service.step_4',compact('type','price','lat','lng','title','user','check'));
        }


//        $all_models = DB::table('all_cars')->where('year', $request->year)->where('make', $request->make)->pluck('model');
//        $all_models = DB::table('all_cars')->where('year', $request->year)->where('make', $request->make)->pluck('model');
//        dd(Auth::user());

    }
          function distance($lat1, $lon1, $lat2, $lon2, $unit) {
          if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
          }
          else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
              return ($miles * 1.609344);
            } else if ($unit == "N") {
              return ($miles * 0.8684);
            } else {
              return $miles;
            }
          }
        }

        // function for get information of place by its place id
    public function getPhoneNumber($placeId)
    {
        $url =  'https://maps.googleapis.com/maps/api/place/details/json?place_id='.$placeId.'&fields=name,rating,formatted_phone_number&key='.config('app.GOOLE_MAP_KEY');
        $placeifo = file_get_contents($url);
        $placeifo = json_decode($placeifo,true);
        if(isset($placeifo['result']['formatted_phone_number'])) {
            $phoneNumber = $placeifo['result']['formatted_phone_number'];
        }else{
            $phoneNumber = "(703) 593-5258";
        }

        $PlaceName   = $placeifo['result']['name'];
        $response = [
            'phone'=>$phoneNumber,
            'name'=>$PlaceName
        ];
        return $phoneNumber;
//        dd($phoneNumber);/
    }
    public function sendMailswoop($req)
    {
        
        $data = [
            'description'=>ucfirst($req['type']),
            'amount'=>$req['price'],
            'total'=>$req['price'],
            'full_name'=>$req['full_name'],
            'email'=>$req['email_']
        ];

        try {
            Mail::send('emails.invoice', $data, function ($m) use ($data) {
                $m->from('email@driveroadside.com', 'DRIVE | Roadside App');
                $m->bcc('billing@driveroadside.com', 'DRIVE | Roadside App');
                $m->to($data['email'],$data['full_name'])->subject('Drive Roadside Assistance');
            });
        }catch (\Exception $e){
            dd($e->getMessage());
            return $e->getMessage();
        }

        try {
            $account_sid = env('TWILIO_SID');
            $auth_token = env('TWILIO_TOKEN');
            $twilio_number = env('TWILIO_NUMBER');

            $client = new Client($account_sid, $auth_token);
            $client->messages->create(
                $req['phone_test'],
                array(
                    'from' => $twilio_number,
                    'body' => 'Thank you for using DRIVE | ROADSIDE. We have received your roadside rescue request and will update you on the status of your rescue within our system. If you don???t receive an update within 15 minutes please call  1 (800) 513-1745 to request an update on the status of your rescue. For billing related inquiries, please email email@driveroadside.com.'
                )
            );
        //            dd($client);$client
        }catch (\Exception $e){Log::info($e->getMessage());}
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
    public function step5(Request $request){
     $t = $this->sendMailswoop($request->all());

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
        $input = $request->all();
        $address = $this->getaddress($input['lat'],$input['lng']);
        $input['addr'] = $address;

        $res =  $this->check_before_create_job();

        if(isset($request['auth_car_id'])){
            $car = Cars::where(['id'=>$request['auth_car_id']])->first();
            $input['make']     = $car->make;
            $input['model']     = $car->model;
            $input['color']     = $car->color;
            $input['year']      = $car->year;
        }
        $user = Auth::user();
        if($user->name != 'Guest User')
        {
            $input['full_name'] = $user->name;
            $input['phone']     = $user->contact_number;
            $input['email']     = $user->email;
            $input['address']     = $user->address;
        }else{
            $user->name = $input['full_name'];
            $user->contact_number = $input['phone'];
//            $user->email          = $input['email_'];
            $user->address        = $input['addr'];
            $user->save();

            $gasLanLat = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$input['lat'].','.$input['lng'].'&key='.config('app.GOOLE_MAP_KEY').'&location');
            $gasLanLat = json_decode($gasLanLat,True);
            $currentAddress = $gasLanLat['results'][0]['formatted_address'];
            $input['address'] = $currentAddress;
            $input['email'] = $input['email_'];
        }

        if($input['type'] == 'battery')
        {
            $json = $this->DeadBatteryJson($input);

            $type = 1;
        }else if($input['type'] == 'tire')
	{
            $type = 2;
            $json = $this->tyreChange($input);


        }else if($input['type'] == 'tow')
        {
            $type = 3;
            $json = $this->towServiceJson($input);

        }else if($input['type'] == 'lockout')
        {
            $type = 4;
            $json = $this->lockoutJson($input);
        }else if($input['type'] == 'winch'){
            $type = 5;
            $json = $this->winchService($input);
        }else{
            $type = 6;
            $json = $this->fuleService($input);
        }

        $resposeData = $this->cUrlRequest($json, $sToken);
        Log::info($resposeData);

        $data = json_decode($resposeData,True);

//

        $data = $data['data']['addJob']['job'];

        $check = 0;
        $user = Auth::user();
        $userdetail = User::find($user->id);
        $userdetail->lat = $request->lat;
        $userdetail->lng = $request->lng;
        $userdetail->save();

        // child converted to parent
        if($user->linked_id != null){
            $parent_user = User::find($user->linked_id);
            $userdetail = $parent_user;
            $child_user = $user;
            $check = 1;
        }else{
            $child_user = $userdetail;
        }
        $JobInput = array('job_id' => $data['id'],
                          'status'=>'pending',
                          'lat'=>$input['lat'],
                          'lng'=>$input['lng'],
                          'type'=>$type,
                          'swoop_token'=>$sToken);
        $JobInput['user_id'] = $user->id;
        $job = Jobs::create($JobInput);

        if(isset($request->payment_id)){
            if(!empty($request->payment_id)){
                Payment::where('id', $request->payment_id)->update(['job_id'=>$job->id]);
            }
        }

        $job_id = Jobs::where('job_id',$data['id'])->first();

        if(!empty($userdetail->getSubscription) && $userdetail->getSubscription->counter > 0){

            $service = new Service();
            $service->user_id = $userdetail->id;
            $service->name = $input['type'];
            $service->job_id = $job_id->id;
            $service->sub_id = $userdetail->getSubscription->id;
            $service->used_by = $child_user->id;

            if(isset($request->miles) && !empty($request->miles) ) {
                $service->miles_covered = $request->miles;
                $service->status = 1;
            }
//            if($name != 'tow'){
            $service->status = 1;
//            }
            $service->save();

            $subscription = Subscription::find($userdetail->getSubscription->id);
//            if($name != 'tow'){
            $subscription->counter = $userdetail->getSubscription->counter - 1;
//            }
            $subscription->save();

        } else {
            $service = Service::where('user_id',$userdetail->id)->where('job_id', null)->where('name',$input['type'])->first();

            if($service) {
                if ($job_id) {
                    $service->job_id = $job_id->id;
                }
                if ($name != 'tow') {
                    $service->status = 1;
                }
                $service->save();
            }
        }
        $lat = $input['lat'];
        $lng = $input['lng'];
        if($check == 1){
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&key=".config('app.GOOLE_MAP_KEY'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $location = json_decode($response)->results[0]->formatted_address;

            $plan_owner = $userdetail;
            $help_needed_to = $child_user;
            $users_to_send_email = User::where('linked_id', $plan_owner->id)->where('id', '!=', $help_needed_to->id)->get()->toArray();
            $users_to_send_email[] = $plan_owner;

            $notification = new NotificationsController();
//            Log::info($users_to_send_email);



            foreach($users_to_send_email as $one){
                $datatosend['family_member'] = ucwords($help_needed_to->name);
                $family_member = $datatosend['family_member'];
                $datatosend['service'] = strtoupper((isset($service->name)?$service->name:''));
                $datatosend['location'] = $location;

                try {

                    Mail::send('emails.job_created', $datatosend, function ($m) use ($one) {
                        $m->from('support@roadside.com', 'Roadside App');
                        $m->to($one->email, $one->name)->subject('Family Member Need Help!');
                    });
                }catch (\Exception $e){
                    return sendSuccess('Job Created Successfully', $job);
                }
                // Send Push Notification to other family
                $notification->sendPushNotification("Hello, your family member $family_member need's help, Service and Location details are mentioned below.", $one->id, $datatosend);
            }

        }
//        Log::info('--------------------------------------$job');
//        Log::info($job);
        // Send text to user from twilio


        $res = array(
            'error' => 0,
            'job_id'=> $job_id->id,
            'lat'=>$lat,
            'lng'=>$lng,
            'message'=>'Job is creates success'
        );
        return response()->json($res);


    }

    public function submitHubspot(Request $req)
    {
        $input = $req->all();
        if(!isset($input['year']))
        {
            $input['year'] = 2020;
        }
        if(isset($input['email_']))
        {
            $email = $input['email_'];
        }else{
        $email = $input['email_user'];
        }
        $input['email'] = $email;
        $data2 = array (
            'fields' =>
                array (

                    0 => array(
                        'name'=>'firstname',
                        'value'=> $input['full_name'],
                    ),
                    1=>array(
                        'name'=>'service',
                        'value'=>$input['type']
                    ),

                    2=>array(
                        'name'=>'vehicle',
                        'value'=>$input['make'].' '.$input['model'].' '.$input['year'],
                    ),
                    3=>array(
                        'name'=>'address',
                        'value'=>$this->getaddress($input['lat'],$input['lng']),
                    ),
                    4=>array(
                        'name'=>'phone',
                        'value'=>$input['phone']
                    ),
                    5 =>
                        array (
                            'name' => 'email',
                            'value' => $input['email'],
                        ),


                ),

            'legalConsentOptions' =>
                array (
                    'consent' =>
                        array (
                            'consentToProcess' => true,
                            'text' => 'I agree to allow Example Company to store and process my personal data.',
                            'communications' =>
                                array (
                                    0 =>
                                        array (
                                            'value' => true,
                                            'subscriptionTypeId' => 999,
                                            'text' => 'I agree to receive marketing communications from Example Company.',
                                        ),
                                ),
                        ),
                ),
        );
        $post_json = json_encode($data2);
        $endpoint = 'https://api.hsforms.com/submissions/v3/integration/submit/' . env('HUBSPOT_PORTAL_ID') . '/' . env('SERVICE_HUBSPOT_FORM_ID');
        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        @curl_setopt($ch, CURLOPT_URL, $endpoint);
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
       $data =curl_exec($ch);

        return "Contact Created!";
    }

    // function for sending text to user from twilio

    public function senMessageToUser($number)
    {

        try {
            $account_sid = env('TWILIO_SID');
            $auth_token = env('TWILIO_TOKEN');
            $twilio_number = env('TWILIO_NUMBER');

            $client = new Client($account_sid, $auth_token);
            $client->messages->create(
                '(986) 689-6659',
                array(
                    'from' => $twilio_number,
                    'body' => 'Thank you for using DRIVE | ROADSIDE. We have received your roadside rescue request and will update you on the status of your rescue within our system. If you don???t receive an update within 15 minutes please call  1 (800) 513-1745 to request an update on the status of your rescue. For billing related inquiries, please email email@driveroadside.com.'
                )
            );
//            dd($client);$client
        }catch (\Exception $e){Log::info($e->gtetMessage());}

    }

    public  function getaddress($lat,$lng)
        {
            $latitude = $lat;
            $longitude = $lng;
            $geocodeFromLatLong = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&key='.config('app.GOOLE_MAP_KEY'));
            $output = json_decode($geocodeFromLatLong);
//            dd($output);
            $status = $output->status;
            $address = ($status=="OK")?$output->results[1]->formatted_address:'';
            return $address;
        }
    public function check_before_create_job(){
        $userdetail = Auth::user();
        if(isset($userdetail->linked_id)){
            $user = User::find($userdetail->linked_id);
            $userdetail = $user;
        }
        if(!empty($userdetail->getSubscription)){
            if ($userdetail->getSubscription->stripe_plan == env('STRIPE_PLAN_MONTHLY_PLAN')){
                $subscription_date = $userdetail->getSubscription->created_at;
                $initial_time_check = Carbon::parse($subscription_date)->addHours(48);
                $first_span_end_date = Carbon::parse($subscription_date)->addMonths(3);
                $second_span_end_date = Carbon::parse($first_span_end_date)->addMonths(3);
                $third_span_end_date = Carbon::parse($second_span_end_date)->addMonths(3);
                $forth_span_end_date = Carbon::parse($third_span_end_date)->addMonths(3);

                if(Carbon::now() < $initial_time_check){
                    $date_time = Carbon::parse($initial_time_check)->format('Y-m-d H:i:s');
                    $response = array(
                        'error' => 1,
                        'message' => 'You have 0 trips remaining'
                    );
                    return response()->json($response);
                }

                if(Carbon::now() < $first_span_end_date){
                    if($userdetail->getSubscription->counter == 4){return sendSuccess('Success', '');}
                    else{
                        $date_time = Carbon::parse($first_span_end_date)->format('Y-m-d H:i:s');
                        $response = array(
                            'error' => 1,
                            'message' => 'You have 0 trips remaining'
                        );
                        return response()->json($response);return $response;
                    }
                }
                if(Carbon::now() > $first_span_end_date && Carbon::now() < $second_span_end_date){
                    if($userdetail->getSubscription->counter == 4 || $userdetail->getSubscription->counter == 3){return sendSuccess('Success', null);}
                    else{
                        $date_time = Carbon::parse($second_span_end_date)->format('Y-m-d H:i:s');
                        $response = array(
                            'error' => 1,
                            'message' => 'You have 0 trips remaining'
                        );
                        return response()->json($response);
                    }
                }
                if(Carbon::now() > $second_span_end_date && Carbon::now() < $third_span_end_date){
                    if($userdetail->getSubscription->counter == 4 || $userdetail->getSubscription->counter == 3 || $userdetail->getSubscription->counter == 2){return sendSuccess('Success', null);}
                    else{
                        $date_time = Carbon::parse($third_span_end_date)->format('Y-m-d H:i:s');
                        $response = array(
                            'error' => 1,
                            'message' => 'You have 0 trips remaining'
                        );
                        return response()->json($response);
                    }
                }
                if(Carbon::now() > $third_span_end_date && Carbon::now() < $forth_span_end_date){
                    if($userdetail->getSubscription->counter == 4 || $userdetail->getSubscription->counter == 3 || $userdetail->getSubscription->counter == 2 || $userdetail->getSubscription->counter == 1){return sendSuccess('Success', null);}
                    else{
                        $date_time = Carbon::parse($forth_span_end_date)->format('Y-m-d H:i:s');
                        $response = array(
                            'error' => 1,
                            'message' => 'You have 0 trips remaining'
                        );
                        return response()->json($response);
                    }
                }
            }
//            if ($userdetail->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN')){
//                if($userdetail->getSubscription->counter == 0){
//                                         $response = array(
//                        'error' => 1,
//                        'message' => 'You have 0 trips remaining'
//                    );
//                    return $response;
//                } else {
//                      $response = array(
//                        'error' => 0,
//                        'message' => 'You have 0 trips remaining'
//                    );
//                    return $response;
//                }
//            }
            if ($userdetail->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_PLUS')){
                if($userdetail->getSubscription->counter == 0){
                    $response = array(
                        'error' => 1,
                        'message' => 'You have 0 trips remaining'
                    );
                    return response()->json($response);
                } else {
                    $response = array(
                        'error' => 0,
                        'message' => 'success'
                    );
                    return response()->json($response);
                }
            }
            if ($userdetail->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR')){
                if($userdetail->getSubscription->counter == 0) {
                    $response = array(
                        'error' => 1,
                        'message' => 'You have 0 trips remaining'
                    );
                    return response()->json($response);
                } else {
                    $response = array(
                        'error' => 0,
                        'message' => 'success'
                    );
                    return response()->json($response);
                }
            }
        }else{
            $response = array(
                'error' => 1,
                'message' => 'You have no subsecriptions'
            );
            return response()->json($response);
        }
    }

    public function payOneTime(Request $req)
    {
        $user = Auth::user();
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $charge = \Stripe\Charge::create([
            'amount' => $req->price*100,
            'currency' => 'usd',
            'customer' => $user->stripe_id,
            'description'=>$req->type." service",
        ]);
        $payment = DB::table('payments')->insert([
            'user_id' => $user->id,
            'charge_id' => $charge->id,
            'amount'=>$req->price,
        ]);
        if($charge->id != Null && $charge->id != "")
        {
            $response = array(
                'error'=>0,
                'message'=>'success payment'
            );
        }else{
            $response = array(
                'error'=>1,
                'message'=>'failed payment'
            );

        }
        return response()->json($response);

    }


    public function payguestuser(Request $req)
    {

        $input = $req->all();
//dd($input);
        $user = Auth::user();
//        Stripe::setApiKey(env('STRIPE_SECRET'));
//        try {

            $curl = curl_init();
            $bytes = random_bytes(20);
            $account = (bin2hex($bytes));

            $amount = $req->price;
            $card_number = $req->number;
            $exp_month = $req->exp_month;
            $exp_year = $req->exp_year;
            $name = $req->name;
            $street = $req->street;
            $city = $req->city;
            $state = $req->state;
            $country = $req->country;
            $zipcode = $req->zipcode;
            $cvc = $req->cvc;
            $comment1 = $req->email.' '.$name.' '.Auth::user()->contact_number;
            $comment2 = $req->type;

            $fields = "TRXTYPE=A&TENDER=C&VENDOR=1DriveRoadside&USER=Devteam&PWD=Drive3mil&PARTNER=PayPal&ACCT=$card_number&EXPDATE=$exp_month$exp_year&AMT=$amount&COMMENT1=$comment1&COMMENT2=$comment2&BILLTOFIRSTNAME=$name&BILLTOSTREET=$street&BILLTOCITY=$city&BILLTOSTATE=$state&BILLTOZIP=$zipcode&BILLTOCOUNTRY=$country&CVV2=$cvc&CUSTIP=&VERBOSITY=HIGH";
//            $fields = "TRXTYPE=S&TENDER=C&VENDOR=1DriveRoadside&USER=Devteam&PWD=Drive3mil&PARTNER=PayPal&ACCT=$card_number&EXPDATE=$exp_month$exp_year&AMT=$amount&COMMENT1=$comment1&COMMENT2=$comment2&BILLTOFIRSTNAME=$name&BILLTOSTREET=$street&BILLTOCITY=$city&BILLTOSTATE=$state&BILLTOZIP=&BILLTOCOUNTRY=$country&CVV2=$cvc&CUSTIP=&VERBOSITY=HIGH";
//dd($fields);
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://payflowpro.paypal.com',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $fields,
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = curl_exec($curl);
            dd(curl_error($curl));
            curl_close($curl);

//            $customer = \Stripe\Customer::create([
//               'description'=> $req->type." service",
//                'address'=>array(
//                    'city'=>$req->city,
//                    'line1'=>$req->street,
//                    'postal_code'=>$req->p_code,
//                    'state'=>$req->state,
//                    'country'=>$req->country
//                ),
//                'name'=>$req->name,
//                'email'=>$req->email,
//            ]);
//
//            $cards = \Stripe\Customer::createSource(
//                $customer->id, [
//                    'source' => $req->token,
//                ]
//            );
//
//            $charge = \Stripe\Charge::create(
//                [
//                    'customer'=>$customer->id,
//                    'currency'=>'USD',
//                    'amount'=>$req->price*100,
//                    'description'=> $req->type." service",
//
//                ]
//            );

        $response = explode('&', $response);
        $searchword1 = 'PNREF';
        $searchword2 = 'RESPMSG';
        $match = array();
        $PNREF = 1;
        $RESPMSG = 2;
        foreach($response as $k=>$v) {
            if(preg_match("/\b$searchword1\b/i", $v)) {
                $match[$k] = $v;
                $PNREF = $k;
            }
            if(preg_match("/\b$searchword2\b/i", $v)) {
                $match[$k] = $v;
                $RESPMSG = $k;
            }
        }

        $PNREF_val = $match[$PNREF];
        $RESPMSG_val = $match[$RESPMSG];

        $point1 = explode("=",$PNREF_val);
        $point2 = explode("=",$RESPMSG_val);

        $res['PNREF'] = $point1[1];
        $res['RESPMSG_val'] = $point2[1];

            if($res['RESPMSG_val'] == 'Approved') {
//                                                                                            &AMT=1.00&ORIGID=B10P4FFEB1AD&CAPTURECOMPLETE=Y&VERBOSITY=HIGH
//                $ref = $res['PNREF'];
//
//                $fields = "VENDOR=1DriveRoadside&USER=Devteam&PWD=Drive3mil&PARTNER=PayPal&TRXTYPE=D&TENDER=C&AMT=$amount&ORIGID=$ref&CAPTURECOMPLETE=Y&VERBOSITY=HIGH";

                $payment = Payment::create([
                    'user_id' => $user->id,
                    'charge_id' => $res['PNREF'],
                    'amount'=> $input['price'],
                    'created_at' => Carbon::now()
                ]);

                $response = array('error'=>0, 'message'=>'Payment success','payment'=>$payment->id);
                return response()->json($response);
            } else {
                $response = array('error' => 1, 'message'=>$res['RESPMSG_val'], 'payment'=>'');
                return response()->json($response);
            }
//        } catch (\Exception $e) {
//            $response = array('error'=>1,'message'=>$e->getMessage());
//            return response()->json($response);
//            $response = array('error'=>1,'message'=>$e->getMessage());
//            return response()->json($response);
//            return redirect()->route('addmoney.paywithstripe');
//        } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
//            $response = array('error'=>1,'message'=>$e->getMessage());
//            return response()->json($response);
//        }
    }

    public function DeadBatteryJson($req)
    {
    if($req['q5'] == 'No')
    {
        return '{"query":"mutation GettingStartedAddJob {\\n
        addJob(input:{\\n
        job:{\\n
        customer: {\\n
        fullName: \\"'.$req['full_name'].'\\",\\n
        phone: \\"'.$req['phone'].'\\"\\n
        },\\n
        service: {\\n
        name: \\"Battery Jump\\",\\n
        symptom: \\"Dead battery\\",\\n
        answers:[\\n
        {\\n
        question: \\"Keys present?\\",\\n
        answer: \\"'.$req['q1'].'\\"\\n
        }\\n
        {\\n
        question: \\"Customer with vehicle?\\",\\n
        answer: \\"'.$req['q2'].'\\"\\n
        }\\n
        {\\n
        question: \\"Did the vehicle stop when running?\\",\\n
        answer: \\"'.$req['q3'].'\\"\\n
        }\\n
        {\\n
        question: \\"Anyone attempted to jump it yet?\\",\\n
        answer: \\"'.$req['q4'].'\\"\\n
        }\\n
        {\\n
        question: \\"Low clearance?\\",\\n
        answer: \\"'.$req['q5'].'\\",\\n
        extra: \\"0\\"\\n
        }\\n
        ]\\n
        },\\n
        vehicle:{\\n
        make:\\"'.$req['make'].'\\",\\n
        model:\\"'.$req['model'].'\\",\\n
        year:'.$req['year'].',\\n
        color:\\"'.$req['color'].'\\"\\n
        },\\n
        location:{\\n
        serviceLocation:{\\n
        address: \\"'.$req['addr'].'\\",\\n
        lat: '.$req['lat'].',\\n
        lng: '.$req['lng'].',\\n
        locationType: \\"Local Roadside\\"\\n
        }\\n
        }\\n
        }}){\\n
        job {\\n
        id\\n
        customer{\\n
        fullName\\n
        phone\\n
        }\\n
        service{\\n
        name\\n
        }\\n
        location{\\n
        serviceLocation {\\n
        address\\n
        }\\n
        }\\n
        }\\n
        }\\n
        }","variables":{}}';
    }else{
        return '{"query":"mutation GettingStartedAddJob {\\n
        addJob(input:{\\n
        job:{\\n
        customer: {\\n
        fullName: \\"'.$req['full_name'].'\\",\\n
        phone: \\"'.$req['phone'].'\\"\\n
        },\\n
        service: {\\n
        name: \\"Battery Jump\\",\\n
        symptom: \\"Dead battery\\",\\n
        answers:[\\n
        {\\n
        question: \\"Keys present?\\",\\n
        answer: \\"'.$req['q1'].'\\"\\n
        }\\n
        {\\n
        question: \\"Customer with vehicle?\\",\\n
        answer: \\"'.$req['q2'].'\\"\\n
        }\\n
        {\\n
        question: \\"Did the vehicle stop when running?\\",\\n
        answer: \\"'.$req['q3'].'\\"\\n
        }\\n
        {\\n
        question: \\"Anyone attempted to jump it yet?\\",\\n
        answer: \\"'.$req['q4'].'\\"\\n
        }\\n
        {\\n
        question: \\"Low clearance?\\",\\n
        answer: \\"'.$req['q5'].'\\",\\n
        extra: \\"4 feet\\"\\n
        }\\n
        ]\\n
        },\\n
        vehicle:{\\n
        make:\\"'.$req['make'].'\\",\\n
        model:\\"'.$req['model'].'\\",\\n
        year:'.$req['year'].',\\n
        color:\\"'.$req['color'].'\\"\\n
        },\\n
        location:{\\n
        serviceLocation:{\\n
        address: \\"'.$req['addr'].'\\",\\n
        lat: '.$req['lat'].',\\n
        lng: '.$req['lng'].',\\n
        locationType: \\"Local Roadside\\"\\n
        }\\n
        }\\n
        }}){\\n
        job {\\n
        id\\n
        customer{\\n
        fullName\\n
        phone\\n
        }\\n
        service{\\n
        name\\n
        }\\n
        location{\\n
        serviceLocation {\\n
        address\\n
        }\\n
        }\\n
        }\\n
        }\\n
        }","variables":{}}';
    }


    }

    public function tyreChange($req)
    {
        if($req['q5'] == 'No')
        {
            return '{"query":"mutation GettingStartedAddJob {\\n
        addJob(input:{\\n
        job:{\\n
        customer: {\\n
        fullName: \\"'.$req['full_name'].'\\",\\n
        phone: \\"'.$req['phone'].'\\"\\n
        },\\n
        service: {\\n
        name: \\"Tire Change\\",\\n
        symptom: \\"Flat tire\\",\\n
        answers:[\\n
        {\\n
        question: \\"Keys present?\\",\\n
        answer: \\"Yes\\"\\n
        }\\n
        {\\n
        question: \\"Customer with vehicle?\\",\\n
        answer: \\"'.$req['q3'].'\\"\\n
        }\\n
        {\\n
        question: \\"Which tire is damaged?\\",\\n
        answer: \\"Driver Front\\"\\n
        }\\n
        {\\n
        question: \\"Have a spare in good condition?\\",\\n
        answer: \\"'.$req['q2'].'\\"\\n
        }\\n
        {\\n
        question: \\"Low clearance?\\",\\n
        answer: \\"'.$req['q5'].'\\",\\n
        extra: \\"0\\"\\n
        }\\n
        ]\\n
        },\\n
        vehicle:{\\n
        make:\\"'.$req['make'].'\\",\\n
        model:\\"'.$req['model'].'\\",\\n
        year:'.(int)$req['year'].',\\n
        color:\\"'.$req['color'].'\\"\\n
        },\\n
        location:{\\n
        serviceLocation:{\\n
        address: \\"'.$req['addr'].'\\",\\n
        lat: '.$req['lat'].',\\n
        lng: '.$req['lng'].',\\n
        locationType: \\"Local Roadside\\"\\n
        }\\n
        }\\n
        }}){\\n
        job {\\n
        id\\n
        customer{\\n
        fullName\\n
        phone\\n
        }\\n
        service{\\n
        name\\n
        }\\n
        location{\\n
        serviceLocation {\\n
        address\\n
        }\\n
        }\\n
        }\\n
        }\\n
        }","variables":{}}';
        }else{
            return '{"query":"mutation GettingStartedAddJob {\\n
        addJob(input:{\\n
        job:{\\n
        customer: {\\n
        fullName: \\"'.$req['full_name'].'\\",\\n
        phone: \\"'.$req['phone'].'\\"\\n
        },\\n
        service: {\\n
        name: \\"Tire Change\\",\\n
        symptom: \\"Flat tire\\",\\n
        answers:[\\n
        {\\n
        question: \\"Keys present?\\",\\n
        answer: \\"Yes\\"\\n
        }\\n
        {\\n
        question: \\"Customer with vehicle?\\",\\n
        answer: \\"'.$req['q3'].'\\"\\n
        }\\n
        {\\n
        question: \\"Which tire is damaged?\\",\\n
        answer: \\"Driver Front\\"\\n
        }\\n
        {\\n
        question: \\"Have a spare in good condition?\\",\\n
        answer: \\"'.$req['q2'].'\\"\\n
        }\\n
        {\\n
        question: \\"Low clearance?\\",\\n
        answer: \\"'.$req['q5'].'\\",\\n
        extra: \\"4 feet\\"\\n
        }\\n
        ]\\n
        },\\n
        vehicle:{\\n
        make:\\"'.$req['make'].'\\",\\n
        model:\\"'.$req['model'].'\\",\\n
        year:'.(int)$req['year'].',\\n
        color:\\"'.$req['color'].'\\"\\n
        },\\n
        location:{\\n
        serviceLocation:{\\n
        address: \\"'.$req['addr'].'\\",\\n
        lat: '.$req['lat'].',\\n
        lng: '.$req['lng'].',\\n
        locationType: \\"Local Roadside\\"\\n
        }\\n
        }\\n
        }}){\\n
        job {\\n
        id\\n
        customer{\\n
        fullName\\n
        phone\\n
        }\\n
        service{\\n
        name\\n
        }\\n
        location{\\n
        serviceLocation {\\n
        address\\n
        }\\n
        }\\n
        }\\n
        }\\n
        }","variables":{}}';
        }
    }


    public function fuleService($req)
    {
       if($req['q3']=='NO')
       {
           return '{"query":"mutation GettingStartedAddJob {\\n
        addJob(input:{\\n
        job:{\\n
        customer: {\\n
        fullName: \\"'.$req['full_name'].'\\",\\n
        phone: \\"'.$req['phone'].'\\"\\n
        },\\n
        service: {\\n
        name: \\"Fuel Delivery\\",\\n
        symptom: \\"Out of fuel\\",\\n
        answers:[\\n
        {\\n
        question: \\"Customer with vehicle?\\",\\n
        answer: \\"'.$req['q1'].'\\"\\n
        }\\n
        {\\n
        question: \\"What is the gasoline type?\\",\\n
        answer: \\"'.$req['q2'].'\\"\\n
        }\\n
        {\\n
        question: \\"Low clearance?\\",\\n
        answer: \\"'.$req['q3'].'\\",\\n
        extra: \\"0\\"\\n
        }\\n
        ]\\n
        },\\n
        vehicle:{\\n
        make:\\"'.$req['make'].'\\",\\n
        model:\\"'.$req['model'].'\\",\\n
        year:'.(int)$req['year'].',\\n
        color:\\"'.$req['color'].'\\"\\n
        },\\n
        location:{\\n
        serviceLocation:{\\n
        address: \\"'.$req['addr'].'\\",\\n
        lat: '.$req['lat'].',\\n
        lng: '.$req['lng'].',\\n
        locationType: \\"Local Roadside\\"\\n
        }\\n
        }\\n
        }}){\\n
        job {\\n
        id\\n
        customer{\\n
        fullName\\n
        phone\\n
        }\\n
        service{\\n
        name\\n
        }\\n
        location{\\n
        serviceLocation {\\n
        address\\n
        }\\n
        }\\n
        }\\n
        }\\n
        }","variables":{}}';
       }else{
           return '{"query":"mutation GettingStartedAddJob {\\n
        addJob(input:{\\n
        job:{\\n
        customer: {\\n
        fullName: \\"'.$req['full_name'].'\\",\\n
        phone: \\"'.$req['phone'].'\\"\\n
        },\\n
        service: {\\n
        name: \\"Fuel Delivery\\",\\n
        symptom: \\"Out of fuel\\",\\n
        answers:[\\n
        {\\n
        question: \\"Customer with vehicle?\\",\\n
        answer: \\"'.$req['q1'].'\\"\\n
        }\\n
        {\\n
        question: \\"What is the gasoline type?\\",\\n
        answer: \\"'.$req['q2'].'\\"\\n
        }\\n
        {\\n
        question: \\"Low clearance?\\",\\n
        answer: \\"'.$req['q3'].'\\",\\n
        extra: \\"4 feet\\"\\n
        }\\n
        ]\\n
        },\\n
        vehicle:{\\n
        make:\\"'.$req['make'].'\\",\\n
        model:\\"'.$req['model'].'\\",\\n
        year:'.(int)$req['year'].',\\n
        color:\\"'.$req['color'].'\\"\\n
        },\\n
        location:{\\n
        serviceLocation:{\\n
        address: \\"'.$req['addr'].'\\",\\n
        lat: '.$req['lat'].',\\n
        lng: '.$req['lng'].',\\n
        locationType: \\"Local Roadside\\"\\n
        }\\n
        }\\n
        }}){\\n
        job {\\n
        id\\n
        customer{\\n
        fullName\\n
        phone\\n
        }\\n
        service{\\n
        name\\n
        }\\n
        location{\\n
        serviceLocation {\\n
        address\\n
        }\\n
        }\\n
        }\\n
        }\\n
        }","variables":{}}';
       }
    }

    public function winchService($req)
    {

        return '{"query":"mutation GettingStartedAddJob {\\n
        addJob(input:{\\n
        job:{\\n
        customer: {\\n
        fullName: \\"'.$req['full_name'].'\\",\\n
        phone: \\"'.$req['phone'].'\\"\\n
        },\\n
        service: {\\n
        name: \\"Winch Out\\",\\n
        symptom: \\"Stuck\\",\\n
        answers:[\\n
        {\\n
        question: \\"Keys present?\\",\\n
        answer: \\"'.$req['q1'].'\\"\\n
        }\\n
        {\\n
        question: \\"Customer with vehicle?\\",\\n
        answer: \\"'.$req['q2'].'\\"\\n
        }\\n
        {\\n
        question: \\"Vehicle can be put in neutral?\\",\\n
        answer: \\"'.$req['q3'].'\\"\\n
        }\\n
        {\\n
        question: \\"Vehicle 4 wheel drive?\\",\\n
        answer: \\"'.$req['q4'].'\\"\\n
        }\\n
        {\\n
        question: \\"Vehicle driveable after winch out?\\",\\n
        answer: \\"'.$req['q5'].'\\"\\n
        }\\n
        {\\n
        question: \\"Within 10 ft of a paved surface?\\",\\n
        answer: \\"'.$req['q6'].'\\"\\n
        }\\n
        {\\n
        question: \\"Low clearance?\\",\\n
        answer: \\"Yes\\",\\n
        extra: \\"4 feet\\"\\n
        }\\n
        ]\\n
        },\\n
        vehicle:{\\n
        make:\\"'.$req['make'].'\\",\\n
        model:\\"'.$req['model'].'\\",\\n
        year:'.(int)$req['year'].',\\n
        color:\\"'.$req['color'].'\\"\\n
        },\\n
        location:{\\n
        serviceLocation:{\\n
        address: \\"'.$req['addr'].'\\",\\n
        lat: '.$req['lat'].',\\n
        lng: '.$req['lng'].',\\n
        locationType: \\"Local Roadside\\"\\n
        }\\n
        }\\n
        }}){\\n
        job {\\n
        id\\n
        customer{\\n
        fullName\\n
        phone\\n
        }\\n
        service{\\n
        name\\n
        }\\n
        location{\\n
        serviceLocation {\\n
        address\\n
        }\\n
        }\\n
        }\\n
        }\\n
        }","variables":{}}';
    }

    public function towServiceJson($req)
    {
        if($req['q7']=='No')
        {
            return '{"query":"mutation GettingStartedAddJob {\\n
        addJob(input:{\\n
        job:{\\n
        customer: {\\n
        fullName: \\"'.$req['full_name'].'\\",\\n
        phone: \\"'.$req['phone'].'\\"\\n
        },\\n
        service: {\\n
        name: \\"Tow\\",\\n
        symptom: \\"Long distance tow\\",\\n
        answers:[\\n
        {\\n
        question: \\"Keys present?\\",\\n
        answer: \\"'.$req['q1'].'\\"\\n
        }\\n
        {\\n
        question: \\"Customer with vehicle?\\",\\n
        answer: \\"'.$req['q2'].'\\"\\n
        }\\n
        {\\n
        question: \\"Vehicle can be put in neutral?\\",\\n
        answer: \\"'.$req['q3'].'\\"\\n
        }\\n
        {\\n
        question: \\"Vehicle 4 wheel drive?\\",\\n
        answer: \\"'.$req['q4'].'\\"\\n
        }\\n
        {\\n
        question: \\"Will customer ride with tow truck?\\",\\n
        answer: \\"No\\"\\n
        }\\n
        {\\n
        question: \\"Low clearance?\\",\\n
        answer: \\"'.$req['q7'].'\\",\\n
        extra: \\"0\\"\\n
        }\\n
        ]\\n
        },\\n
        vehicle:{\\n
        make:\\"'.$req['make'].'\\",\\n
        model:\\"'.$req['model'].'\\",\\n
        year:'.(int)$req['year'].',\\n
        color:\\"'.$req['color'].'\\"\\n
        },\\n
        location:{\\n
        serviceLocation:{\\n
        address: \\"'.$req['addr'].'\\",\\n
        lat: '.$req['lat'].',\\n
        lng: '.$req['lng'].',\\n
        locationType: \\"Local Roadside\\"\\n
        }\\n
        dropoffLocation:{\\n
        address: \\"'.$req['destinationaddress'].'\\",\\n
        lat:'.$req['destinationLat'].',\\n
        lng: '.$req['destinationLng'].',\\n
        locationType: \\"Local Roadside\\"\\n
        }\\n
        }\\n
        }}){\\n
        job {\\n
        id\\n
        customer{\\n
        fullName\\n
        phone\\n
        }\\n
        service{\\n
        name\\n
        }\\n
        location{\\n
        serviceLocation {\\n
        address\\n
        }\\n
        }\\n
        }\\n
        }\\n
        }","variables":{}}';
        }else{
            return '{"query":"mutation GettingStartedAddJob {\\n
        addJob(input:{\\n
        job:{\\n
        customer: {\\n
        fullName: \\"'.$req['full_name'].'\\",\\n
        phone: \\"'.$req['phone'].'\\"\\n
        },\\n
        service: {\\n
        name: \\"Tow\\",\\n
        symptom: \\"Long distance tow\\",\\n
        answers:[\\n
        {\\n
        question: \\"Keys present?\\",\\n
        answer: \\"'.$req['q1'].'\\"\\n
        }\\n
        {\\n
        question: \\"Customer with vehicle?\\",\\n
        answer: \\"'.$req['q2'].'\\"\\n
        }\\n
        {\\n
        question: \\"Vehicle can be put in neutral?\\",\\n
        answer: \\"'.$req['q3'].'\\"\\n
        }\\n
        {\\n
        question: \\"Vehicle 4 wheel drive?\\",\\n
        answer: \\"'.$req['q4'].'\\"\\n
        }\\n
        {\\n
        question: \\"Will customer ride with tow truck?\\",\\n
        answer: \\"No\\"\\n
        }\\n
        {\\n
        question: \\"Low clearance?\\",\\n
        answer: \\"'.$req['q7'].'\\",\\n
        extra: \\"4 feet\\"\\n
        }\\n
        ]\\n
        },\\n
        vehicle:{\\n
        make:\\"'.$req['make'].'\\",\\n
        model:\\"'.$req['model'].'\\",\\n
        year:'.(int)$req['year'].',\\n
        color:\\"'.$req['color'].'\\"\\n
        },\\n
        location:{\\n
        serviceLocation:{\\n
        address: \\"'.$req['addr'].'\\",\\n
        lat: '.$req['lat'].',\\n
        lng: '.$req['lng'].',\\n
        locationType: \\"Local Roadside\\"\\n
        }\\n
        dropoffLocation:{\\n
        address: \\"'.$req['destinationaddress'].'\\",\\n
        lat:'.$req['destinationLat'].',\\n
        lng: '.$req['destinationLng'].',\\n
        locationType: \\"Local Roadside\\"\\n
        }\\n
        }\\n
        }}){\\n
        job {\\n
        id\\n
        customer{\\n
        fullName\\n
        phone\\n
        }\\n
        service{\\n
        name\\n
        }\\n
        location{\\n
        serviceLocation {\\n
        address\\n
        }\\n
        }\\n
        }\\n
        }\\n
        }","variables":{}}';
        }



    }

    public function lockoutJson($req)
    {
        if($req['q3']=='NO')
        {
            return '{"query":"mutation GettingStartedAddJob {\\n'
                . ' addJob(input:{\\n'
                . ' job:{\\n\\n'
                . '   customer: {\\n'
                . '       fullName: \\"'.$req['full_name'].'\\",\\n'
                . '        phone: \\"'.$req['phone'].'\\"\\n'
                . '            },\\n\\n'
                . '   service: {\\n'
                . '        name: \\"Lock Out\\",\\n'
                . '        symptom: \\"Locked out\\",\\n'
                . '   answers:[\\n'
                . '    {\\n '
                . '       question: \\"Is the vehicle on or running?\\",\\n'
                . '       answer: \\"'.$req['q5'].'\\"\\n '
                . '    }\\n '
                . '    {\\n question: \\"Customer with vehicle?\\",\\n '
                . '       answer: \\"'.$req['q2'].'\\"\\n'
                . '    }\\n'
                . '    {\\n question: \\"Where are the keys located?\\",\\n '
                . '       answer: \\"'.$req['q7'].'\\"\\n'
                . '    }\\n'
                . '    {\\n question: \\"Children or pets locked inside?\\",\\n '
                . '       answer: \\"'.$req['q4'].'\\"\\n'
                . '    }\\n'
//          . '    {\\n question: \\"Will customer ride with tow truck?\\",\\n '
//          . '       answer: \\"No\\"\\n'
//          . '    }\\n'
                . '    {\\n'
                . '     question: \\"Low clearance?\\",\\n'
                . '      answer: \\"'.$req['q3'].'\\",\\n '
                . '   extra: \\"0\\"\\n'
                . '    }\\n '
                . '   ]\\n'
                . ' },\\n\\n '
                . 'vehicle:{\\n '
                . 'make:\\"'.$req['make'].'\\",\\n '
                . 'model:\\"'.$req['model'].' S\\",\\n '
                . 'year:'.(int)$req['year'].',\\n '
                . 'color:\\"'.$req['color'].'\\"\\n'
                . ' },'
                . '\\n location:{\\n '
                . 'serviceLocation:{\\n '
                . 'address: \\"'.$req['address'].'\\",\\n '
                . 'lat: '.$req['lat'].',\\n '
                . 'lng: '.$req['lng'].',\\n '
                . 'locationType: \\"Local Roadside\\"\\n '
                . '}\\n '

                . '}\\n '
                . '}\\n '
                . '})'
                . '{\\n '
                . 'job {\\n '
                . 'id\\n '
                . 'customer{\\n '
                . 'fullName\\n '
                . 'phone\\n }\\n '
                . 'service{\\n '
                . 'name\\n }\\n '
                . 'location{\\n '
                . 'serviceLocation '
                . '{\\n address\\n }\\n'
                . ' }\\n '
                . '}\\n '
                . '}\\n}",'
                . '"variables":{'
                . '}'
                . '}';
        }else{
            return '{"query":"mutation GettingStartedAddJob {\\n'
                . ' addJob(input:{\\n'
                . ' job:{\\n\\n'
                . '   customer: {\\n'
                . '       fullName: \\"'.$req['full_name'].'\\",\\n'
                . '        phone: \\"'.$req['phone'].'\\"\\n'
                . '            },\\n\\n'
                . '   service: {\\n'
                . '        name: \\"Lock Out\\",\\n'
                . '        symptom: \\"Locked out\\",\\n'
                . '   answers:[\\n'
                . '    {\\n '
                . '       question: \\"Is the vehicle on or running?\\",\\n'
                . '       answer: \\"'.$req['q5'].'\\"\\n '
                . '    }\\n '
                . '    {\\n question: \\"Customer with vehicle?\\",\\n '
                . '       answer: \\"'.$req['q2'].'\\"\\n'
                . '    }\\n'
                . '    {\\n question: \\"Where are the keys located?\\",\\n '
                . '       answer: \\"'.$req['q7'].'\\"\\n'
                . '    }\\n'
                . '    {\\n question: \\"Children or pets locked inside?\\",\\n '
                . '       answer: \\"'.$req['q4'].'\\"\\n'
                . '    }\\n'
//          . '    {\\n question: \\"Will customer ride with tow truck?\\",\\n '
//          . '       answer: \\"No\\"\\n'
//          . '    }\\n'
                . '    {\\n'
                . '     question: \\"Low clearance?\\",\\n'
                . '      answer: \\"'.$req['q3'].'\\",\\n '
                . '   extra: \\"4 feet\\"\\n'
                . '    }\\n '
                . '   ]\\n'
                . ' },\\n\\n '
                . 'vehicle:{\\n '
                . 'make:\\"'.$req['make'].'\\",\\n '
                . 'model:\\"'.$req['model'].' S\\",\\n '
                . 'year:'.(int)$req['year'].',\\n '
                . 'color:\\"'.$req['color'].'\\"\\n'
                . ' },'
                . '\\n location:{\\n '
                . 'serviceLocation:{\\n '
                . 'address: \\"'.$req['address'].'\\",\\n '
                . 'lat: '.$req['lat'].',\\n '
                . 'lng: '.$req['lng'].',\\n '
                . 'locationType: \\"Local Roadside\\"\\n '
                . '}\\n '

                . '}\\n '
                . '}\\n '
                . '})'
                . '{\\n '
                . 'job {\\n '
                . 'id\\n '
                . 'customer{\\n '
                . 'fullName\\n '
                . 'phone\\n }\\n '
                . 'service{\\n '
                . 'name\\n }\\n '
                . 'location{\\n '
                . 'serviceLocation '
                . '{\\n address\\n }\\n'
                . ' }\\n '
                . '}\\n '
                . '}\\n}",'
                . '"variables":{'
                . '}'
                . '}';
        }

    }

    public function jobdetail($job_id)
    {
        $user = Auth::user();
        $title = 'Job Status';
        $job = Jobs::where('id',$job_id)->pluck('job_id')->first();

        $lat = Jobs::where('id',$job_id)->pluck('lat')->first();
        $lng = Jobs::where('id',$job_id)->pluck('lng')->first();
        $type = Jobs::where('id',$job_id)->pluck('type')->first();
        $sToken = \App\SwoopToken::first();
        $sToken = $sToken->token;
        $sToken = \App\SwoopToken::first();
        $sToken = $sToken->token;
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', env('SWOOP_PATH_STAGING'), ['headers' => ['Authorization' => 'Bearer wgk2wy4n_vAWDYeryOWxsmr_Nag8lyoQnsT6crZcbr4'],
                                                                              'form_params' => ['query' => 'query getStatus {
                         job(id: "' . $job . '") {
                            id
                            swcid
                            createdAt
                            status
                            partner {
                              name
                              phone
                              driver {
                                name
                                phone
                              }
                              vehicle {
                                location {
                                  lat
                                  lng
                                }
                              }
                            }
                            eta {
                              current
                            }
                          }
                        }']]);

        $json = json_decode($res->getBody());

        $jobId = $json->data->job->id;

        $driver = $json->data->job->partner;
        $job_id = $job_id;
//
        return view('service.jobstatus',compact('title','user','jobId','job_id','driver','lat','lng','type'));
    }

    // function for getting swoop status for job
    public function getJobStatus(Request $req)
    {
        $job_id = $req->jobId;
        $user = Auth::user();
        $title = 'Job Status';
        $job = Jobs::where('id',$job_id)->pluck('job_id')->first();
        $sToken = \App\SwoopToken::first();
        $sToken = $sToken->token;

        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', env('SWOOP_PATH_STAGING'), ['headers' => ['Authorization' => 'Bearer '.$sToken],
            'form_params' => ['query' => 'query {
                            job(id: "' . $job . '") {
                            status
                             eta {
                                  current
                                }
                              partner {
                                vehicle {
                                    location {
                                    lat
                                    lng
                                  }
                                }
                              }
                            }
                          }']]);

        $json = json_decode($res->getBody());
       if($json->data->job->eta->current != null)
       {
           $yourDate = $json->data->job->eta->current;
           $data=date('Y-m-d h:i:s', strtotime($yourDate));
           $time = explode(' ',$data);
           $time = strtotime($time[1]);

           $currentTime = time();
           $mints = (int)$time-(int)$currentTime*60;
           Log::info((int)$time-(int)$currentTime.'eta swoop');

       }else{
           $mints = 0;
       }
        $response = ['driver'=>$json->data->job->partner,'alldata'=>$json,'eta'=>$json->data->job->eta,'mints'=>$mints];
        return response()->json($response);
    }

    public function cancelJob(Request $re)
    {
        $reason = new cancelationReasons;
        $reason->user_id = Auth::id();
        $reason->reason  = $re->reason;
        $reason->detail_reason = $re->notes;
        $reason->cancel_type   = 'Service';
        $reason->save();

        $sToken = \App\SwoopToken::first();
        $sToken = $sToken->token;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SWOOP_PATH_STAGING'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"query":"mutation EditJobStatus {\\neditJobStatus(input: {\\njob: {\\nid: \\"'.$re->jobId.'\\"\\nstatus: Canceled\\n}\\n}){\\njob {\\nid\\nstatus\\n}\\n}\\n}","variables":{}}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$sToken,
                'Content-Type: application/json',
                'Cookie: __cfduid=def798704f46f72a6393f9dc87e4246d21616061127'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $user = Auth::user();
        $userdetail = User::find($user->id);

// refund the rest of money to client in case of 5 minutes
        $date = new \DateTime();
        $date->modify('-5 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $job_id = Jobs::where('job_id',$re->jobId)->first();
        $job_created_time = Carbon::parse($job_id->created_at)
            ->addMinutes(4)
            ->format('Y-m-d H:i:s');

//        if(Carbon::now()->format('Y-m-d H:i:s') >= $job_created_time){
            $this->refund_after_time($job_id);
//        }


        if($userdetail->name == 'Guest User')
        {
            $jobId = $re->jobId;
            $job_id = Jobs::where('job_id',$jobId)->first();
            $job_id->status = 'canceled';
            $job_id->save();

            return redirect('/rescue');
        }else{
            $job_id = Jobs::where('job_id',$re->jobId)->first();
            if(!empty($job_id)){
                $job_id->status = 'canceled';
                $job_id->save();
                $jobId = $re->jobId;

            }
            $service = Service::where('user_id',$user->id)->where('job_id', $job_id->id)->delete();
            $subscription = '';
            if(!empty($subscription)){
                $subscription->counter = $userdetail->getSubscription->counter +1;
                $subscription->save();
            }
        }

        $response = array(
            'error' => 0,
            'message'=>'Job is canceled success'
        );
        return redirect('/rescue');
    }


    public function refund_after_time($job_id)
    {
        $payment = Payment::where('job_id', $job_id->id)->first();
        $amount = 45;
        $charge_id = $payment->charge_id;

        if (!empty($payment)) {
            $curl = curl_init();

            $fields = "VENDOR=1DriveRoadside&USER=Devteam&PWD=Drive3mil&PARTNER=PayPal&TRXTYPE=D&TENDER=C&AMT=$amount&ORIGID=$charge_id&CAPTURECOMPLETE=Y&VERBOSITY=HIGH";

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://payflowpro.paypal.com',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $fields,
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            $response = explode('&', $response);
            $searchword1 = 'PNREF';
            $searchword2 = 'RESPMSG';
            $match = array();
            $PNREF = 1;
            $RESPMSG = 2;
            foreach ($response as $k => $v) {
                if (preg_match("/\b$searchword1\b/i", $v)) {
                    $match[$k] = $v;
                    $PNREF = $k;
                }
                if (preg_match("/\b$searchword2\b/i", $v)) {
                    $match[$k] = $v;
                    $RESPMSG = $k;
                }
            }

            $PNREF_val = $match[$PNREF];
            $RESPMSG_val = $match[$RESPMSG];

            $point1 = explode("=", $PNREF_val);
            $point2 = explode("=", $RESPMSG_val);

            $res['PNREF'] = $point1[1];
            $res['RESPMSG_val'] = $point2[1];

            if ($res['PNREF']) {
                $payment->paypal_charge_id = $res['PNREF'];
                $payment->save();
            }
        }
    }


    public function cUrlRequest($jsonData,$swoopToken)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://app.joinswoop.com/graphql',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$jsonData,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$swoopToken,
                'Content-Type: application/json',
                'Cookie: __cfduid=def798704f46f72a6393f9dc87e4246d21616061127'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public function excel_import(Request $request)
    {
        Excel::import(new CarImport, $request->file);

        return redirect('/')->with('success', 'All good!');
    }
public function upload(Request $request)
    {
        return view('welcome');

    }

public function pay_via_payflow_pay(Request $request){
$curl = curl_init();

    $account = $request->card;
    $amount = $request->amount;
    $exp = $request->exp_date;
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://payflowpro.paypal.com',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'PARTNER=PayPal&PWD=Drive3mil&VENDOR=1DriveRoadside&USER=Devteam&TENDER=C&ACCT='.$account.'&TRXTYPE=S&EXPDATE=1221&AMT='.$amount.'',
  CURLOPT_HTTPHEADER => array(
    'Accept: application/json',
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$response = explode('&', $response);
$searchword = 'RESPMSG';
                            $match = array();
                            $index = 3;
                            foreach($response as $k=>$v) {
                                if(preg_match("/\b$searchword\b/i", $v)) {
                                    $match[$k] = $v;
                                    $index = $k;
                                }
                            }

                            $points = $match[$index];
                            $point = explode("=",$points);

                               $res = $point[1];

	if($res == "Invalid account number"){
		echo false;

	}    else {

		echo true;
	}
}







public function pay_via_payflow(Request $request){
        $curl = curl_init();
        $bytes = random_bytes(20);
        $account = (bin2hex($bytes));

        $amount = $request->amount;

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://payflowpro.paypal.com',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => 'PARTNER=PayPal&PWD=Drive3mil&VENDOR=1DriveRoadside&USER=Devteam&TENDER=C&SECURETOKENID='.$account.'&TRXTYPE=S&CREATESECURETOKEN=Y&AMT='.$amount.'',
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = explode('&', $response);
        $searchword1 = 'SECURETOKEN';
        $searchword2 = 'SECURETOKENID';
        $match = array();
        $secureToken = 1;
        $secureTokenId = 2;
        foreach($response as $k=>$v) {
            if(preg_match("/\b$searchword1\b/i", $v)) {
                $match[$k] = $v;
                $secureToken = $k;
            }
            if(preg_match("/\b$searchword2\b/i", $v)) {
                $match[$k] = $v;
                $secureTokenId = $k;
            }
        }

    $secureToken_val = $match[$secureToken];
    $secureTokenId_val = $match[$secureTokenId];

    $point1 = explode("=",$secureToken_val);
    $point2 = explode("=",$secureTokenId_val);

   $res['secure_token'] = $point1[1];
   $res['secure_token_id'] = $point2[1];
	return $res;

}


public function payflow_thankyou(Request $request)
{
    $data = $request->all();
    return view('payment-progress',compact('data'));
}


    public function changePassword(Request $request) {

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ], 200);
        }
        $user = Auth()->user();
        if ((Hash::check($request->get('old_password'), $user->password))) {
            if ((Hash::check($request->get('password'), $user->password))) {

                return response()->json([
                    'status' => false,
                    'message' => "You can't set the new password as old password!",
                    'data' => null
                ], 200);

            }
            $password = $request->password;
            $user->password = bcrypt($password);
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'User details fetch successful.',
                'data' => $request->user()
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Incorrect old password',
                'data' => null
            ], 200);

        }
    }


       public function submitHubspottest(Request $req)
    {

        $data2 = array (
            'fields' =>
                array (

                    0 => array(
                        'name'=>'firstname',
                        'value'=> 'asdasdfasdfa',
                    ),
                    1=>array(
                        'name'=>'service',
                        'value'=>'tire'
                    ),

                    2=>array(
                        'name'=>'vehicle',
                        'value'=>'Civic 2021 Black',
                    ),
                    3=>array(
                        'name'=>'address',
                        'value'=>'wania wala gujranwala Pakistan',
                    ),
                    4=>array(
                        'name'=>'phone',
                        'value'=>'74102539800'
                    ),
                   5 =>
                        array (
                            'name' => 'email',
                            'value' => 'alitoorheerqwqw@gmail.com',
                        ),


                ),

            'legalConsentOptions' =>
                array (
                    'consent' =>
                        array (
                            'consentToProcess' => true,
                            'text' => 'I agree to allow Example Company to store and process my personal data.',
                            'communications' =>
                                array (
                                    0 =>
                                        array (
                                            'value' => true,
                                            'subscriptionTypeId' => 999,
                                            'text' => 'I agree to receive marketing communications from Example Company.',
                                        ),
                                ),
                        ),
                ),
        );

        $post_json = json_encode($data2);
        $endpoint = 'https://api.hsforms.com/submissions/v3/integration/submit/' . env('HUBSPOT_PORTAL_ID') . '/' . env('SERVICE_HUBSPOT_FORM_ID');
        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        @curl_setopt($ch, CURLOPT_URL, $endpoint);
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
       $data =curl_exec($ch);
       dd($data);
        return "Contact Created!";
    }

    public function seopages()
    {
        if (!Auth::check()) {
            $user = User::create([
                'name' => 'Guest User',
                'email' => str_random(10) . '@gmail.com',
                'password' => bcrypt('123456'),
                'user_type' => 'guest',
            ]);
            $user->user_type = 'guest';
            $user->save();
            if (Auth::loginUsingId($user->id)) {
                $data['user'] = Auth::user();
            }
        } else {
            $user = Auth::user();
            $userId = Auth::id();
            $userJobs = Jobs::where([
                ['user_id', '=', $userId],
                ['status', '!=', 'completed'],
                ['status', '!=', 'deleted'],
                ['status', '!=', 'canceled']
            ])->first();
            if ($userJobs) {
                return redirect()->route('jobdetail', $userJobs->id);
            }
            return view('citypages.nyc',compact('title','user'));
        }
    }

    public function cityPages()
    {
        $uri = explode('/',\request()->getRequestUri())[2];
        // dd($uri);
        $title = 'step 3';
        switch ($uri){

            case 'roadside-assistance-newyork':
                $location = "NEW YORK";
                $pageName = 'citypages.nyc';
                break;
            case 'roadside-assistance-chicago':
                $location = "Chicago";
                $pageName = 'citypages.chicago';
                break;
            case 'roadside-assistance-dallas':
                $location = 'Dallas';
                $pageName = 'citypages.dallas';
                break;
            case 'roadside-assistance-houston':
                $location = 'Houston';
                $pageName = 'citypages.houston';
                break;
            case 'roadside-assistance-los_angles':
                $location = 'Los Angles';
                $pageName = 'citypages.la';
                break;
            case 'roadside-assistance-orlando':
                $location = "Orlando";
                $pageName = 'citypages.orlando';
                break;
            case 'roadside-assistance-atlanta':
                $location = 'Atlanta';
                $pageName = 'citypages.atlanta';
                break;
            case 'roadside-assistance-charlotte':
                $location = 'Charlotte';
                $pageName = 'citypages.charlotte';
                break;
            case 'roadside-assistance-philadelphia':
                $location = 'Philadelphia';
                $pageName = 'citypages.philadephia';
                break;
            case 'roadside-assistance-miami':
                $location = 'Miami';
                $pageName = 'citypages.miami';
                break;
            case 'roadside-assistance-denvor':
                $location = 'Denvor';
                $pageName = 'citypages.denver';
                break;
            case 'roadside-assistance-boston':
                $location = 'Boston';
                $pageName = 'citypages.boston';
                break;
            case 'roadside-assistance-tampa':
                $location = 'Tampa';
                $pageName = 'citypages.tampa';
                break;
            case 'roadside-assistance-washington':
                $location = 'Washington';
                $pageName = 'citypages.washington';
                break;
            case 'roadside-assistance-los_vegas':
                $location = 'Los Vegas';
                $pageName = 'citypages.losvegas';
                break;
            case 'roadside-assistance-san_francisco':
                $location = 'San Francisco';
                $pageName = 'citypages.san_francisco';
                break;
            case 'roadside-assistance-nashville':
                $location = 'nashville';
                $pageName = 'citypages.nashville';
                break;
            case 'roadside-assistance-detroit':
                $location = 'detroit';
                $pageName = 'citypages.detroit';
                break;
            case 'roadside-assistance-memphis':
                $location = 'memphis';
                $pageName = 'citypages.memphis';
                break;
            case 'roadside-assistance-phoenix':
                $location = 'Phoenix';
                $pageName = 'citypages.phoenix';
                break;
        }

        if (!Auth::check()) {
            $user = User::create([
                'name' => 'Guest User',
                'email' => str_random(10) . '@gmail.com',
                'password' => bcrypt('123456'),
                'user_type' => 'guest',
            ]);
            $user->user_type = 'guest';
            $user->save();
            if (Auth::loginUsingId($user->id)) {
                $data['user'] = Auth::user();
            }
        }else{
            $user = Auth::user();
            $userId = Auth::id();
            $userJobs = Jobs::where([
                ['user_id','=',$userId],
                ['status','!=','completed'],
                ['status','!=','deleted'],
                ['status','!=','canceled']
            ])->first();
            if($userJobs)
            {
                return redirect()->route('jobdetail', $userJobs->id);
            }
        }
        return view($pageName,compact('title','user','location'));
    }

    public function teststatus($id)
    {
        $sToken = \App\SwoopToken::first();
        $sToken = $sToken->token;

        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', env('SWOOP_PATH_STAGING'), ['headers' => ['Authorization' => 'Bearer '.$sToken],
            'form_params' => ['query' => 'query {
                            job(id: "' . $id . '") {
                            status
                             eta {
                                  current
                                }
                              partner {
                                vehicle {
                                    location {
                                    lat
                                    lng
                                  }
                                }
                              }
                            }
                          }']]);

        $json = json_decode($res->getBody());
        dd($json);
    }

    public function senMessageToUsertest()
    {

        try {
            $account_sid = env('TWILIO_SID');
            $auth_token = env('TWILIO_TOKEN');
            $twilio_number = env('TWILIO_NUMBER');

            $client = new Client($account_sid, $auth_token);
            $client->messages->create(
                '(986) 689-6659',
                array(
                    'from' => $twilio_number,
                    'body' => 'Thank you for using DRIVE | ROADSIDE. We have received your roadside rescue request and will update you on the status of your rescue within our system. If you don???t receive an update within 15 minutes please call  1 (800) 513-1745 to request an update on the status of your rescue. For billing related inquiries, please email email@driveroadside.com.'
                )
            );
//            dd($client);$client
        }catch (\Exception $e){Log::info($e->gtetMessage());}

    }

}
