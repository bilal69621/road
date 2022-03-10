<?php

namespace App\Http\Controllers;

use App\Jobs;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OneSignal;
use View;
use App\Reminders;

class NotificationsController extends Controller {

public function sendPushNotification($text, $user_id, $data){
    Log::info('notification1');
                $content = array(
			"en" => $text
			);

		$fields = array(
			'app_id' => config('onesignal.app_id'), //"cfb64ad7-429b-419d-944b-765cfba6cfb5",
			'included_segments' => array('Active Users'),
			'filters' => array(array("field" => "tag", "key" => "user_id", "relation" => "=", "value" => "$user_id")),
			'data' => $data,
			'contents' => $content,
            'priority' => 10,
		);

		$fields = json_encode($fields);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												   'Authorization: Basic  '.config('onesignal.rest_api_key')));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
//		echo true;
		 return $response;
}

public function sendPushNotification2($text, $user_id, $data){
$content = array(
			"en" => $text
			);

		$fields = array(
			'app_id' => config('onesignal.app_id_2'), //"cfb64ad7-429b-419d-944b-765cfba6cfb5",
			'included_segments' => array('Active Users'),
			'filters' => array(array("field" => "tag", "key" => "user_id", "relation" => "=", "value" => "$user_id")),
			'data' => $data,
			'contents' => $content
		);

		$fields = json_encode($fields);
    	print("\nJSON sent:\n");
    	print($fields);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												   'Authorization: Basic  '.config('onesignal.rest_api_key_2')));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
		echo true;
		// return $response;
}



    public function cron() {
        $path = storage_path();
        if( chmod($path, 0777) ) {
            chmod($path, 0777);
        }
        $sToken = \App\SwoopToken::first();
        $sToken = $sToken->token;

	// $this->sendPushNotification("Status : ",151,  array("foo" => "bar"));

//        https://staging.joinswoop.com/graphql

        $jobs = DB::table('jobs')->whereNotIn('status', ['completed', 'canceled', 'goa','released ', 'reassigned'])->get();

        $client = new \GuzzleHttp\Client();
        foreach ($jobs as $job) {



            $dataToSend['job_id'] = $job->job_id;
            $status = $job->status;
            $res = $client->request('POST', 'https://app.joinswoop.com/graphql', ['headers' => ['Authorization' => 'Bearer '.$sToken],
                'form_params' => ['query' => 'query getStatus {
                         job(id: "' . $job->job_id . '") {
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
            if (isset($json->data->job) && $status != strtolower($json->data->job->status) && $json->data->job->status != 'Canceled') {


                 $dataToSend['status'] = $json->data->job->status;
                if (strtolower($json->data->job->status) == 'accepted') {
                    $dataToSend['statuss'] = "Rescue Accepted";
                } else if (strtolower($json->data->job->status) == 'assigned') {
                    $dataToSend['statuss'] = "Pending";
                } else if ($job->type == 2 && strtolower($json->data->job->status) == 'towdestination') {
                    $dataToSend['statuss'] = "Dropping Off";
                } else if ($job->type != 2 && (strtolower($json->data->job->status) == 'towing' || strtolower($json->data->job->status) == 'towdestination' )) {
                    $dataToSend['statuss'] = "In Progress";
                } else if (strtolower($json->data->job->status) == 'enroute') {
                    $dataToSend['statuss'] = "En Route";
                } else {
                    $dataToSend['statuss'] = $json->data->job->status;
                }
                $dataToSend['job_id'] = $job->job_id;
		        $this->sendPushNotification("Status : " . $dataToSend['statuss'],$job->user_id, $dataToSend);
                $this->sendPushNotification2("Status : " . $dataToSend['statuss'],$job->user_id, $dataToSend);

// OneSignal::sendNotificationUsingTags(
                 //       "Status : " . $dataToSend['statuss'], array(
                   // ["field" => "tag", "key" => 'user_id', "relation" => "=", "value" => $job->user_id],
                    //    ), $url = null, $data = $dataToSend, $buttons = null, $schedule = null
               // );
//Log::info($json->data->job->status);

                // Check if job is completed or goa
//                if($json->data->job->status == 'completed' || $json->data->job->status == 'goa'){
//                    $this->pay_on_completion($job->job_id, $json->data->job->status);
//                }

                DB::table('jobs')->where('job_id', $job->job_id)->update(['status' => strtolower($json->data->job->status)]);
// echo 'dubeg221' ; exit;

            } else if (isset($json->data->job) && $status != strtolower($json->data->job->status) && $json->data->job->status == 'Canceled') {

                $this->refund_after_time($job);
                DB::table('jobs')->where('job_id', $job->job_id)->update(['status' => strtolower($json->data->job->status)]);


            }
        }
    }


    public function cron_extra() {
        $path = storage_path();
        if( chmod($path, 0777) ) {
            chmod($path, 0777);
        }

        $jobIds = Payment::where('id','>','3032')->whereNull('paypal_charge_id')->select('job_id as id')->get()->toArray();

        $jobs = DB::table('jobs')->whereIn('id',$jobIds)->where('status','completed')->get();

        foreach ($jobs as $check_job) {

            if($check_job->status == 'completed' || $check_job->status == 'goa') {

                $payment = Payment::where('job_id',$check_job->id)->whereNull('paypal_charge_id')->first();
                $amount = $payment->amount;
                $charge_id = $payment->charge_id;

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
//            $payment->save();
//                $payment = DB::table('payments')->insert([
//                    'user_id' => $user->id,
//                    'charge_id' => $res['PNREF'],
//                    'amount'=> $input['price'],
//                    'created_at' => Carbon::now()
//                ]);

            }
        }
    }




    public function cron_extra_canceled() {
        $path = storage_path();
        if( chmod($path, 0777) ) {
            chmod($path, 0777);
        }

        $jobIds = Payment::where('id','>','3032')->whereNull('paypal_charge_id')->select('job_id as id')->get()->toArray();

        $jobs = DB::table('jobs')->whereIn('id',$jobIds)->where('status','canceled')->get();

        foreach ($jobs as $check_job) {

            if($check_job->status == 'canceled') {

                $payment = Payment::where('job_id',$check_job->id)->whereNull('paypal_charge_id')->first();
                $amount = 45;
                $charge_id = $payment->charge_id;

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
//            $payment->save();
//                $payment = DB::table('payments')->insert([
//                    'user_id' => $user->id,
//                    'charge_id' => $res['PNREF'],
//                    'amount'=> $input['price'],
//                    'created_at' => Carbon::now()
//                ]);

            }
        }
    }




    public function refund_after_time($job_id)
    {
        $payment = Payment::where('job_id', $job_id->id)->first();
        if(!empty($payment)){

            if($payment->id > 1820){

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

        }
    }


    public function pay_on_completion($jobId, $jobStatus)
    {
        $job = Jobs::where('job_id',$jobId)->first();
        $payment = Payment::where('job_id', $job->id)->first();
        $amount = $payment->amount;
        $charge_id = $payment->charge_id;

        if(!empty($payment)){
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

            if($res['PNREF']) {
                $payment->paypal_charge_id = $res['PNREF'];
                $payment->save();
            }
//            $payment->save();
//                $payment = DB::table('payments')->insert([
//                    'user_id' => $user->id,
//                    'charge_id' => $res['PNREF'],
//                    'amount'=> $input['price'],
//                    'created_at' => Carbon::now()
//                ]);


        }
    }


    public function pay_on_completion_check()
    {
        $path = storage_path();
        if( chmod($path, 0777) ) {
            chmod($path, 0777);
        }
        $ids = Jobs::whereIn('status',['completed','goa'])->select('id')->get()->toArray();
        $payment = Payment::whereIn('job_id',$ids)->whereNotNull('job_id')->whereNull('paypal_charge_id')->first();

        if(!empty($payment)) {
            $amount = $payment->amount;
            $charge_id = $payment->charge_id;

            if (!empty($payment)) {
                $check_job = Jobs::find($payment->job_id);
                if ($check_job->status == 'completed' || $check_job->status == 'goa') {

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
//            $payment->save();
//                $payment = DB::table('payments')->insert([
//                    'user_id' => $user->id,
//                    'charge_id' => $res['PNREF'],
//                    'amount'=> $input['price'],
//                    'created_at' => Carbon::now()
//                ]);

                }
            }
        }
    }



    public function getLocation($job_id) {

        $sToken = \App\SwoopToken::first();
        $sToken = $sToken->token;
//        https://staging.joinswoop.com/graphql
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST', 'https://app.joinswoop.com/graphql', ['headers' => ['Authorization' => 'Bearer '.$sToken],
            'form_params' => ['query' => 'query {
                            job(id: "' . $job_id . '") {
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

        $location = json_decode($res->getBody());
        if ($location->data->job->partner->vehicle) {
            view('location', ['job_id' => $job_id, 'lat' => $location->data->job->partner->vehicle->location->lat, 'lng' => $location->data->job->partner->vehicle->location->lng])->render();
        } else {
            return view('location', ['job_id' => $job_id, 'lat' => '0.00', 'lng' => '0.00'])->render();
        }
    }

    public function sendReminders() {
        $reminders = DB::table('reminders')->where('all_sent', 0)->get();
        $date_today = date('Y-m-d');
        $time_today = strtotime(date('H:i:s'));

        foreach ($reminders as $reminder) {
            $update_reminders = Reminders::find($reminder->id);
            if (strpos($update_reminders->time_zone, '+') !== false) {
                $bool = true;
            } else {
                $bool = false;
            }
            $add = substr($update_reminders->time_zone, 1);
            if ($reminder->purchase_sent == 0 && $reminder->purchase_date != null) {
                if ($bool) {
                    if ($reminder->purchase_date == $date_today && (strtotime($reminder->purchase_time) - ($add * 3600)) <= $time_today) {
                        $dataToSend['description'] = $reminder->purchase_description;
                        OneSignal::sendNotificationUsingTags(
                                $reminder->purchase_description, array(
                            ["field" => "tag", "key" => 'user_id', "relation" => "=", "value" => $reminder->user_id],
                                ), $url = null, $data = $dataToSend, $buttons = null, $schedule = null, ucfirst($reminder->title)
                        );
                        $update_reminders->purchase_sent = 1;
                    }
                } else {
                    if ($reminder->purchase_date == $date_today && (strtotime($reminder->purchase_time) + ($add * 3600)) <= $time_today) {
                        $dataToSend['description'] = $reminder->purchase_description;
                        OneSignal::sendNotificationUsingTags(
                                $reminder->purchase_description, array(
                            ["field" => "tag", "key" => 'user_id', "relation" => "=", "value" => $reminder->user_id],
                                ), $url = null, $data = $dataToSend, $buttons = null, $schedule = null, ucfirst($reminder->title)
                        );
                        $update_reminders->purchase_sent = 1;
                    }
                }
            }
            if ($reminder->insurance_sent == 0 && $reminder->insurance_date != null) {
                if ($bool) {
                    if ($reminder->insurance_date == $date_today && (strtotime($reminder->insurance_time) - ($add * 3600)) <= $time_today) {
                        $dataToSend['description'] = $reminder->insurance_description;
                        OneSignal::sendNotificationUsingTags(
                                $reminder->insurance_description, array(
                            ["field" => "tag", "key" => 'user_id', "relation" => "=", "value" => $reminder->user_id],
                                ), $url = null, $data = $dataToSend, $buttons = null, $schedule = null, ucfirst($reminder->title)
                        );
                        $update_reminders->insurance_sent = 1;
                    }
                } else {
                    if ($reminder->insurance_date == $date_today && (strtotime($reminder->insurance_time) + ($add * 3600)) <= $time_today) {
                        $dataToSend['description'] = $reminder->insurance_description;
                        OneSignal::sendNotificationUsingTags(
                                $reminder->insurance_description, array(
                            ["field" => "tag", "key" => 'user_id', "relation" => "=", "value" => $reminder->user_id],
                                ), $url = null, $data = $dataToSend, $buttons = null, $schedule = null, ucfirst($reminder->title)
                        );
                        $update_reminders->insurance_sent = 1;
                    }
                }
            }
            if ($reminder->maintainence_sent == 0 && $reminder->maintainence_date != null) {
                if ($bool) {
                    if ($reminder->maintainence_date == $date_today && (strtotime($reminder->maintainence_time) - ($add * 3600)) <= $time_today) {
                        $dataToSend['description'] = $reminder->maintainence_description;
                        OneSignal::sendNotificationUsingTags(
                                $reminder->maintainence_description, array(
                            ["field" => "tag", "key" => 'user_id', "relation" => "=", "value" => $reminder->user_id],
                                ), $url = null, $data = $dataToSend, $buttons = null, $schedule = null, ucfirst($reminder->title)
                        );
                        $update_reminders->maintainence_sent = 1;
                    }
                } else {
                    if ($reminder->maintainence_date == $date_today && (strtotime($reminder->maintainence_time) + ($add * 3600)) <= $time_today) {
                        $dataToSend['description'] = $reminder->maintainence_description;
                        OneSignal::sendNotificationUsingTags(
                                $reminder->maintainence_description, array(
                            ["field" => "tag", "key" => 'user_id', "relation" => "=", "value" => $reminder->user_id],
                                ), $url = null, $data = $dataToSend, $buttons = null, $schedule = null, ucfirst($reminder->title)
                        );
                        $update_reminders->maintainence_sent = 1;
                    }
                }
            }

            if ($update_reminders->maintainence_sent == 1 && $update_reminders->purchase_sent == 1 && $update_reminders->insurance_sent == 1) {
                $update_reminders->all_sent = 1;
            }
            $update_reminders->save();
        }
    }

    public function getStatus($job_id) {

        $sToken = \App\SwoopToken::first();
        $sToken = $sToken->token;

        $client = new \GuzzleHttp\Client();
//           https://staging.joinswoop.com/graphql
        $res = $client->request('POST', 'https://app.joinswoop.com/graphql', ['headers' => ['Authorization' => 'Bearer '.$sToken],
            'form_params' => ['query' => 'query getStatus {
                          job(id: "' . $job_id . '") {
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
        if (isset($json->data->job) && isset($json->data->job->status)) {
            $status = $json->data->job->status;
            return sendSuccess('Status', $status);
        }
        return sendError('No such job', 404);
    }


//    public function  check($name, $phone, $q1, $q2, $clearanceType, $value, $car, $CarModel) {
//            return "mutation AddJob {
//                 addJob(input: {" +
//                "    job: {" +
//                "      status: Draft" +
//                "      customer: {" +
//                "        fullName: \"$name\"," +
//                "        phone: \"$phone\"" +
//                "      }," +
//                "      notes:{" +
//                "        customer: \"${Param.stimulateJob}\"," +
//                //            "        internal: \"\"" +
//                "      }" +
//                "      " +
//                "      service:{" +
//                "        name: \"Fuel Delivery\"" +
//                "        symptom: \"Out of fuel\"" +
//                "        answers:[" +
//                "          {" +
//                "            question: \"Customer with vehicle?\"" +
//                "            answer: \"$q1\"" +
//                "          }" +
//                "          {" +
//                "            q
//                uestion: \"What is the gasoline type?\"" +
//                "            answer: \"$q2\"" +
//                "          }" +
//                "          {" +
//                "            question: \"Low clearance?\"" +
//                "            answer: \"$clearanceType\" " +
//                "            extra: \"$value\" " +
//                "          }" +
//                "        ]" +
//                "      }," +
//                "      " +
//                "      vehicle:{" +
//                "        make: \"${car.make}\"," +
//                "        model: \"${car.model}\"," +
//                "        year: ${car.year}," +
//                "        color: \"${car.color}\"" +
////            "        license: \"W00T\"," +
////            "        odometer: 1337," +
////            "        vin:\"1GNSKBE04DR265715\"" +
//                "      }," +
//                "" +
//                "      location:{
//      " +
//                "        serviceLocation:{" +
//                "          address: \"$address\"," +
//                "          lat: $lat," +
//                "          lng: $lng," +
//                "          locationType:\"Local Roadside\"" +
//                "        }," +
////            "        dropoffLocation:{" +
////            "          address: \"379 S Van Ness Ave, San Francisco, CA 94103, USA\"," +
////            "          lat: 37.767186640067," +
////            "          lng: -122.41756439209," +
////            "          locationType:\"Local Roadside\"," +
////            "          googlePlaceId: \"ChIJ7ShCECR-j4ARXWzJd1LoB30\"" +
////            "        }," +
//                "        pickupContact: {" +
//                "          fullName: \"$name\"," +
//                "          phone: \"$phone\"" +
//                "        }" +
////            "        dropoffContact: {" +
////            "          fullName: \"Droppy McDropoff\"," +
////            "          phone: \"+18888675309\"" +
////            "        }" +
//                "      }," +
//                "      " +
//                "      texts:{" +
//                "        sendLocation: false," +
//                "        sendEtaUpdates: false," +
//                "        sendDriverTracking: false," +
//                "        sendReview: false," +
//                "        sendTextsToPickup: false" +
//                "      }" +
//                "    }" +
//                "  }){" +
//                "    job {" +
//                "      id" +
//                "      notes {" +
//                "        customer" +
//                "        internal" +
//                "      }" +
//                "      service {" +
//                "        name" +
//                "        symptom" +
//                "        answers {" +
//                "          edges {" +
//                "            node {" +
//                "              question" +
//                "              answer" +
//                "              extra" +
//                "            }" +
//                "          }" +
//                "        }" +
//                "      }" +
//                "      vehicle {" +
//                "        make" +
//                "        model" +
//                "        year" +
//                "        color" +
//                "        license" +
//                "        odometer" +
//                "        vin" +
//                "      }" +
//                "      location {      " +
//                "        serviceLocation {" +
//                "          address" +
//                "          lat" +
//                "          lng" +
//                "          locationType\n" +
//                "        }\n" +
//                "        dropoffLocation {\n" +
//                "          address\n" +
//                "          lat\n" +
//                "          lng\n" +
//                "          locationType\n" +
//                "          googlePlaceId\n" +
//                "        }\n" +
//                "        pickupContact {\n" +
//                "          fullName\n" +
//                "          phone\n" +
//                "        }\n" +
//                "        dropoffContact {\n" +
//                "          fullName\n" +
//                "          phone\n" +
//                "        }\n" +
//                "      }\n" +
//                "      texts {\n" +
//                "        sendLocation\n" +
//                "        sendEtaUpdates\n" +
//                "        sendDriverTracking\n" +
//                "        sendReview\n" +
//                "        sendTextsToPickup\n" +
//                "      }\n" +
//                "    }\n" +
//                "  }\n" +
//                "}";
//    }

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


}


//}
