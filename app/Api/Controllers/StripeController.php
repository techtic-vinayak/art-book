<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use Stripe;
use App\Models\Art;
use App\Models\ArtPayment;

class StripeController extends Controller
{
    public function postPaymentStripe(Request $request)
    {
        $user_id = Auth::id();
        $art = Art::find($request->id);
        Stripe\Stripe::setApiKey('sk_test_51LNEIxSIzhjcOeUkNv62ZB4TCm64HH6EvdZUDlyvWqnrM1FJ1Qq7nrejvCtq7D8DmYLvjgoHgipywYdMC7gb7Jq600SbBu4NWN');
        $charge_amount  = number_format((htmlentities(30) * 100), 0, '.', '');
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
}
