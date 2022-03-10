<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\NotificationsController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Cars;
use App\Jobs;
use App\Subscription;
use App\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Validator;

class JobsController extends Controller
{
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|string|unique:jobs',
            'status' => 'required|string',
            'lat' => 'string',
            'lng' => 'string',
            'type' => 'string',
            'swoop_token' => 'string',
        ]);
        if ($validator->fails()) {
            return sendError($validator->errors(), 401);
        }
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
        // end
//
//        if(!empty($userdetail->getSubscription) && $userdetail->getSubscription->counter == 0){
//            return sendError('You have 0 trips remaining.', 200);
//        }

        $input = $request->all();
        $input['user_id'] = $user->id;
        $job = Jobs::create($input);

         //name = {locksmith,   tire_change,   fuel_delivery,   tow,    jumpstart }

         if($request->type == 0){
             $name = "jumpstart";
         } else if($request->type == 1){
             $name = "locksmith";
         } else if($request->type == 2){
             $name = "tow";
         } else if($request->type == 3){
             $name = "tire_change";
         } else if($request->type == 4){
             $name = "fuel_delivery";
         }

         $job_id = Jobs::where('job_id',$request->job_id)->first();

         if(!empty($userdetail->getSubscription) && $userdetail->getSubscription->counter > 0){

            $service = new Service();
            $service->user_id = $userdetail->id;
            $service->name = $name;
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
            $service = Service::where('user_id',$userdetail->id)->where('job_id', null)->where('name',$name)->first();

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
        $lat = $request->lat;
        $lng = $request->lng;
// Send Email Start
        if($check == 1){
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&key=AIzaSyBhMoC9OLQs1fxPJkPWxdgC9dui6pIKQoA",
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
            Log::info($users_to_send_email);
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
        Log::info('--------------------------------------$job');
        Log::info($job);
        return sendSuccess('Job Created Successfully', $job);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = Jobs::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if (!$job)
           return sendError('No such job exists', 401);

        return sendSuccess('Job exists', $job);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $job = Jobs::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if (!$job)
            return sendError('No such job exists', 401);

        return sendSuccess('Edit Job', $job);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:jobs',
        ]);
        if ($validator->fails()) {
            return sendError($validator->errors(), 401);
        }

        $job = Jobs::where('id', $request->id)->where('user_id', Auth::user()->id)->first();
        if (!$job)
            return sendError('No such job exists', 401);

        $job->update($request->all());
        return sendSuccess('Job Successfully Updated', $job);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Jobs::where('id', $id)->where('user_id', Auth::user()->id)->delete();
        if ($result) {
            return sendSuccess('Job Successfully Removed', $result);
        }

        return sendError('No such job exists', 401);
    }

    public function all_jobs()
    {
        $jobs = Jobs::where('user_id', Auth::user()->id)->get();
        if ($jobs->isEmpty())
            return sendError('No jobs added', 404);

        return sendSuccess('All Jobs', $jobs);
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
                    return sendError("You can use any trip after ($date_time).", 200);
                }

                if(Carbon::now() < $first_span_end_date){
                    if($userdetail->getSubscription->counter == 4){return sendSuccess('Success', '');}
                    else{
                        $date_time = Carbon::parse($first_span_end_date)->format('Y-m-d H:i:s');
                        return sendError("Next trip will be available at  ($date_time).", 200);
                    }
                }
                if(Carbon::now() > $first_span_end_date && Carbon::now() < $second_span_end_date){
                    if($userdetail->getSubscription->counter == 4 || $userdetail->getSubscription->counter == 3){return sendSuccess('Success', null);}
                    else{
                        $date_time = Carbon::parse($second_span_end_date)->format('Y-m-d H:i:s');
                        return sendError("Next trip will be available at  ($date_time).", 200);
                    }
                }
                if(Carbon::now() > $second_span_end_date && Carbon::now() < $third_span_end_date){
                    if($userdetail->getSubscription->counter == 4 || $userdetail->getSubscription->counter == 3 || $userdetail->getSubscription->counter == 2){return sendSuccess('Success', null);}
                    else{
                        $date_time = Carbon::parse($third_span_end_date)->format('Y-m-d H:i:s');
                        return sendError("Next trip will be available at  ($date_time).", 200);
                    }
                }
                if(Carbon::now() > $third_span_end_date && Carbon::now() < $forth_span_end_date){
                    if($userdetail->getSubscription->counter == 4 || $userdetail->getSubscription->counter == 3 || $userdetail->getSubscription->counter == 2 || $userdetail->getSubscription->counter == 1){return sendSuccess('Success', null);}
                    else{
                        $date_time = Carbon::parse($forth_span_end_date)->format('Y-m-d H:i:s');
                        return sendError("Next trip will be available at  ($date_time).", 200);
                    }
                }
            }
            if ($userdetail->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN') || $userdetail->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_FAMILY_PLAN_1')){
                if($userdetail->getSubscription->counter == 0){
                    return sendError('You have 0 trips remaining.', 200);
                } else {
                    return sendSuccess('Success', '');
                }
            }
            if ($userdetail->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR_PLUS')){
                if($userdetail->getSubscription->counter == 0){
                    return sendError('You have 0 trips remaining.', 200);
                } else {
                    return sendSuccess('Success', '');
                }
            }
            if ($userdetail->getSubscription->stripe_plan == env('STRIPE_PLAN_1_YEAR')){
                if($userdetail->getSubscription->counter == 0) {
                    return sendError('You have 0 trips remaining.', 200);
                } else {
                    return sendSuccess('Success', '');
                }
            }
        }else{
            return sendError('You have no subscription.', 200);
        }
    }

    public function cancel_job(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|string',
        ]);
        if ($validator->fails()) {
            return sendError($validator->errors(), 401);
        }
        $user = Auth::user();
        $userdetail = User::find($user->id);
        $job_id = Jobs::where('job_id',$request->job_id)->first();
        if(!empty($job_id)){
            $job_id->status = 'canceled';
	    $job_id->save();
        }
        $service = Service::where('user_id',$user->id)->where('job_id', $job_id->id)->delete();

        $subscription = Subscription::find($userdetail->getSubscription->id);
        if(!empty($subscription)){
            $subscription->counter = $userdetail->getSubscription->counter +1;
            $subscription->save();
        }

        return sendSuccess('Job Canceled Successfully', $job_id);

    }
}
