<?php

namespace App\Console\Commands;

use App\Jobs;
use App\Payment;
use Illuminate\Console\Command;

class PayOnCompletionCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delayed:capture';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
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
}
