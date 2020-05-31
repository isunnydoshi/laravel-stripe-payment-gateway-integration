<?php

namespace App\Http\Controllers;

use App\PaymentLogs;
use Illuminate\Http\Request;

class PaymentLogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth'); // if you want user to be logged in to use this function then uncomment this code.
    }

    public function handleonlinepay(Request $request){  
        $input = $request->input();
        
        try {
            \Stripe\Stripe::setApiKey(config('constants.STRIPE_SECRET'));
            
            //if currency in 'USD' then require to create customer. (Exporting to International Customers: check official document: https://stripe.com/docs/india-exports)
            // Creating a customer - If you want to create customer uncomment below code.
            $customer = \Stripe\Customer::create(array(
                'name' => 'Sam John',
                'email' => 'john@gmail.co',
                'source' => $request->stripeToken,
                'address' => [
                    'city' => 'San Francisco', 
                    'state' => 'CA',
                    'country' => 'US', 
                    'line1' => '510 Townsend St',  
                    'postal_code' => '98140',
                ],
            ));
            
            $unique_id = uniqid(); // just for tracking purpose incase you want to describe something.

            //if currency in 'USD' then require to create customer. Charge to customer
            $charge = \Stripe\Charge::create ([
                'customer' => $customer->id,  //comment if you are using currency 'INR' so do not require to create customer.
                'description' => "Plan: ".$input['plan']." - Amount: $".$input['amount'].' - '. $unique_id,
                'amount' => $input['amount'] * 100, // the amount will be consider as cent so we need to multiply with 100
                'currency' => 'USD',
            ]);
            
            // Uncomment this if currency in 'INR'. So do not require to create customer.
            /*$charge = \Stripe\Charge::create ([
                'description' => "Plan: ".$input['plan']." - Amount: INR".$input['amount'].' - '. $unique_id,
                'source' => $request->stripeToken,
                'amount' => $input['amount'] * 100, // the amount will be consider as cent so we need to multiply with 100
                'currency' => 'INR',   
            ]);*/

            // Insert into the database
            $paymentlogs = new PaymentLogs();
            $paymentlogs['amount'] = $input['amount'];
            $paymentlogs['plan'] = $input['plan'];
            $paymentlogs['charge_id'] = $charge->id;
            $paymentlogs['stripe_id'] = $unique_id;
            $paymentlogs['quantity'] = 1;
            $paymentlogs->savePaymentData($paymentlogs); 
            
            return response()->json([
                'message' => 'Charge successful, Thank you for payment!',
                'state' => 'success'
            ]);                
        } catch(\Stripe\Exception\CardException $e) {
            // Since it's a decline, \Stripe\Exception\CardException will be caught
            echo 'Status is:' . $e->getHttpStatus() . '\n';
            echo 'Type is:' . $e->getError()->type . '\n';
            echo 'Code is:' . $e->getError()->code . '\n';
            // param is '' in this case
            echo 'Param is:' . $e->getError()->param . '\n';
            echo 'Message is:' . $e->getError()->message . '\n';
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
            echo "Catch 2"; print_r($e);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            echo "Catch 3"; print_r($e);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            echo "Catch 4"; print_r($e);
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            echo "Catch 5"; print_r($e);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            echo "Catch 6"; print_r($e);
        } catch (\Exception $e) {
            // Something else happened, completely unrelated to Stripe
            echo "Catch 7"; print_r($e);
            return response()->json([
                'message' => 'There were some issue with the payment. Please try again later.',
                'state' => 'error'
            ]);
        }       
    }
}