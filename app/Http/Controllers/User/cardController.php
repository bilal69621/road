<?php
namespace App\Http\Controllers\User;
use App\Cars;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\card;
use App\User;
use Stripe\Stripe;
class cardController extends Controller
{
    function __construct()
    {

    }
     public function add_card(Request $request)
    {

         $user = Auth::user();
         Stripe::setApiKey(env('STRIPE_SECRET'));
         $customer = \Stripe\Customer::create(
                 [
                     'name'=>$user->name,
                       'phone'=>$user->contact_number,
                       'address'=>array(
                           'city'=>$user->city,
                           'line1'=>$user->address,
                           'postal_code'=>$user->zipcode,
                           'state'=>$user->state,
                           'country'=>$user->country
                       ),
                       'email'=>$user->email,
                ]
            );

        $cards = \Stripe\Customer::createSource(
            $customer->id, [

                'source' => $request->stripeToken,
            ]
        );


         $user_id = $user->id;
         $is_default = True;
         if ($is_default) {
            card::where('user_id', $user_id)->update(['is_default' => false]);
        } else {
            $check = card::where('user_id', $user_id)->where('is_default', true)->first();
            if (!$check) {
                $is_default = true;
            }
        }
        $card = card::where('stripe_id', $user->stripe_id)->first();
        if (!$card) {
            $card = new Card();
            $card->user_id = $user_id;
            $card->stripe_id =$customer->id;
        }
        $user->stripe_id = $customer->id;
        $user->card_brand = $cards->brand;
        $user->card_last_four = $cards->last4;
        $user->save();
        $card->stripe_id = $customer->id;
        $card->card_holder_name = $cards->name;
        $card->brand = $cards->brand;
        $card->last4 = $cards->last4;
        $card->exp_month = $cards->exp_month;
        $card->exp_year = $cards->exp_year;
        $card->country = $cards->country;
        $card->is_default = $is_default;
        $card->save();
        return redirect('/paymentinfo');
    }

     public function deleteCard(Request $request)
     {
         $result = card::where('id', $request->cardId)->where('user_id', Auth::user()->id)->delete();
         if ($result) {
             $response = array(
                 'error'=>0,
                 'message'=>'card deleted successfully',
             );
             return response()->json($response);
         }

         $response = array(
             'error'=>1,
             'message'=>'Some thing went wrong',
         );
         return response()->json($response);
     }

     public function getGassPrices($lat,$lng)
     {
         $locations = array();
         $userLat = '';
         $userLng = '';
         $user = Auth::user();

        $gasLanLat = file_get_contents('https://maps.googleapis.com/maps/api/place/nearbysearch/json?key='.config('app.GOOLE_MAP_KEY').'&location='.$lat.','.$lng.'&radius=1000&types=gas_station');
        $gasLanLat = json_decode($gasLanLat);
//        dd($gasLanLat);
        foreach($gasLanLat->results as $latLan)
        {

            array_push($locations,$latLan->geometry->location);
        }

        return view('users.gasPrice')->with(['title'=>'Gass Prices','user'=>Auth::user(),'gassLocations'=>$locations,'lat'=>$lat,'lng'=>$lng]);
     }

     public function getlocation()
     {
           $ip = $this->getUserIP();
                if (\Request::ip() && \Request::ip() != '::1') {
                    $ip = \Request::ip();
                }
                var_dump($ip);exit;
                $access_key = '3f75a96d9d201dcd49b125095b78d0f3';
                $location = json_decode(file_get_contents('http://api.ipstack.com/' . $ip . '?access_key=' . $access_key));
                if ($location->continent_code   ) {
                    $lat = $location->latitude;
                    $lng = $location->longitude;
                }
                return $lat;
     }

     function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}


     public function subsecriptions()
     {
         $user = Auth::user();
         $title = 'Membership Plans';
         $planIds = array();
         array_push($planIds, env('STRIPE_PLAN_1_YEAR') );
         array_push($planIds, env('STRIPE_PLAN_1_YEAR_PLUS') );
         Stripe::setApiKey(env('STRIPE_SECRET'));
         $plans = array();
         foreach($planIds as $PlanId)
         {
             $plan = \Stripe\Plan::retrieve($PlanId);
             array_push($plans,$plan);
         }
          $userSubscriptions = User::where('id', $user->id)->with('getSubscription')->first();
//        dd($userSubscriptions);
       return view('users.membershipPlans')->with(['user'=>$user,'title'=>$title,'plans'=>$plans,'userSub'=>$userSubscriptions]);
     }
}

