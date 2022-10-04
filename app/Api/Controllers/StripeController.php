<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use Stripe;
use App\Models\Art;
use App\Models\ArtPayment;
use App\Models\StripeMeta;
use App\Models\User;
use App\Api\Requests\AddStripeAccountRequest as AddStripeAccount;
use Config;

class StripeController extends Controller
{
    public function postPaymentStripe(Request $request)
    {
        $user_id = Auth::id();
        $art = Art::find($request->id);
        Stripe\Stripe::setApiKey(Config::get('constants.strip.STRIPE_SECRET'));
        $charge_amount  = number_format((htmlentities($art->price) * 100), 0, '.', '');
        try 
        {
            $intents = Stripe\PaymentIntent::create([
                'amount'               => $charge_amount,
                'currency'             => 'usd',
                'payment_method_types' => ['card'],
            ]);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $charge = $intents->confirm(
                ['payment_method' => 'pm_card_visa']
            );  
            \Log::info(print_r($charge, true));
        } catch (\Stripe\Exception\CardException $e) {
            return response()->json([
                'status_code' => 400,
                'message'     => $e->getMessage(),
            ], 400);
        } catch (\Stripe\Exception\RateLimitException $e) {
            return response()->json([
                'status_code' => 400,
                'message'     => $e->getMessage(),
            ], 400);
            // Too many requests made to the API too quickly
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return response()->json([
                'status_code' => 400,
                'message'     => $e->getMessage(),
            ], 400);
            // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return response()->json([
                'status_code' => 400,
                'message'     => $e->getMessage(),
            ], 400);
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return response()->json([
                'status_code' => 400,
                'message'     => $e->getMessage(),
            ], 400);
            // Network communication with Stripe failed
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json([
                'status_code' => 400,
                'message'     => $e->getMessage(),
            ], 400);
            // Display a very generic error to the user, and maybe send
            // yourself an email
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 400,
                'message'     => 'Something wrong. please try again.',
            ], 400);
            // Something else happened, completely unrelated to Stripe
        }
        if (!empty($charge)) {

        if (isset($charge->status)) {
            $status = $charge->status;
        } else {
            $status = "Something went wrong.";
        }
        $payment = ArtPayment::create([
            "art_id" => $art->id,
            "to_user_id" => $user_id,
            "from_user_id" => $art->user_id,
            "is_payment_done" => 1
        ]);
        return response()->json([
            'status_code' => 200,
            'data'        => $payment,
        ], 200);
      }  
    }

    public function store(AddStripeAccount $request)
    {

        $userId = $request->state;
        //$userId = Auth::id();
    	$code = $request->code;
       
    	if (empty($userId)) {
            return response()->json([
                    'status_code' => 400,
                    'message'     => 'Oops something went wrong, User Id not found.',
            ], 400);
    	} else  if (empty($code)) {
            return response()->json([
                    'status_code' => 400,
                    'message'     => 'Oops something went wrong, Code not found.',
            ], 400);
    	}
    	 ///echo "string";exit;
        \Stripe\Stripe::setApiKey(Config::get('constants.strip.STRIPE_SECRET'));
        session_start();
    	$response = \Stripe\OAuth::token([
    		'grant_type' => 'authorization_code',
    		'code' => $code
    	]);

    	$stripe_acc_id = $response->stripe_user_id;

    	$add_stripe_meta = array(
    		'meta_key' 		=> 	$code,
    		'meta_value'    => 	$stripe_acc_id
    	);

    	$stripe_meta = StripeMeta::create($add_stripe_meta);
    	if(!empty($stripe_meta)){

    		$uset_detail = User::where('id',$userId)->first();

    		if (empty($uset_detail)) {
                return response()->json([
                    'status_code' => 400,
                    'message'     => 'Oops something went wrong, User not found.',
                ], 400);
    		}

    		$uset_detail->stripe_id = $stripe_acc_id;
    		$uset_detail->save();

    		return "<h1 style='text-align: center; margin-top: 7%;font-size: 60px;'>Thank You</h1>";
    	}else{
            return response()->json([
                    'status_code' => 400,
                    'message'     => 'Oops something went wrong.',
            ], 400);
    	}
    }
}
