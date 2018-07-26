<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cartalyst\Stripe\Stripe;
use Cartalyst\Stripe\Customer;
use Cartalyst\Stripe\Charge;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index()
    {
    	return view('payment.index');
    }

    protected function validator(array $data, $type=false)
    {
        $messages = [];

        if($type == 'task1'){

	        return Validator::make($data, [
	            'card_no' => 'required',
	            'ccExpiryMonth' => 'required|date_format:m',
	            'ccExpiryYear' => 'required|date_format:Y',
	            'cvvNumber' => 'required|numeric|min:3',
	            'amount' => 'required|numeric',
	        ],$messages);
	    }
	    else if ($type == 'task2')
	    {
	    	return Validator::make($data, [
	            /*'plan_name' => 'required',
	            'currency' => 'required',
	            'amount' => 'required|numeric',*/
	        ],$messages);
	    }
	    else if ($type == 'task3')
	    {
	    	return Validator::make($data, [
	            /*'plan_name' => 'required',
	            'currency' => 'required',
	            'amount' => 'required|numeric',*/
	        ],$messages);
	    }
    }

    public function charge(Request $request)
    {
        
        $data = $request->all();
        $validator = $this->validator($data, 'task1');

        if ($validator->fails())
        {           
            $result = array('status' => false, 'message' => $validator->errors()->first(), 'code' => '');
        }
        else
        {            
            $stripe = new Stripe(env('STRIPE_API_KEY'));
            try {
                $token = $stripe->tokens()->create([
                    'card' => [
                        'number'    => $request->get('card_no'),
                        'exp_month' => $request->get('ccExpiryMonth'),
                        'exp_year'  => $request->get('ccExpiryYear'),
                        'cvc'       => $request->get('cvvNumber'),
                    ],
                ]);
                if (!isset($token['id'])) {
                    $result = array('status' => false,'message' => 'The Stripe Token was not generated correctly', 'code' => '');
                }
                $customer = $stripe->customers()->create([
					'email' => $request->get('email'),
					'source'  => $token['id'],
				]);
                $charge = $stripe->charges()->create([
                    'customer' => $customer['id'],
                    'currency' => 'USD',
                    'amount'   => $request->get('amount'),
                    'description' => 'Add in wallet',
                ]);
                if($charge['status'] == 'succeeded') {
                    /**
                    * Write Here Your Database insert logic.
                    */
                    $result = array('status' => false,'message' => 'Transaction Successful', 'code' => '');
                } else {
                    $result = array('status' => false,'message' => 'Transaction Not Successful', 'code' => '');
                }
            } catch (Exception $e) {
                $result = array('status' => false,'message' => $e->getMessage(), 'code' => '');
            } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
                $result = array('status' => false,'message' => $e->getMessage(), 'code' => '');
            } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                $result = array('status' => false,'message' => $e->getMessage(), 'code' => '');
            }
        }
        return json_encode($result);
    }

    public function monthlyPlan()
    {

    	return view('monthly-plan.index');
    }

    public function createMonthlyPlan(Request $request)
    {
    	$data = $request->all();
        $validator = $this->validator($data, 'task2');

        if ($validator->fails())
        {           
            $result = array('status' => false, 'message' => $validator->errors()->first(), 'code' => '');
        }
        else
        {           
            try {
                $charge = [
                	'id'    => $request->get('plan_id'),
                    'amount' => $request->get('plan_amount'),
                    'currency'  => $request->get('plan_currency'),
                    'interval'       => $request->get('plan_interval'),
                    'nickname'       => $request->get('plan_name'),
                    'product' =>  'prod_DIf3Xfa6j2qVcK',
                ];

                $ch = curl_init('https://api.stripe.com/v1/plans');
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.env('STRIPE_API_KEY')));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($charge));

				// execute!
				$response = curl_exec($ch);

				// close the connection, release resources used
				curl_close($ch);

                if($response) {
                    /**
                    * Write Here Your Database insert logic.
                    */
                    $result = array('status' => false,'message' => $response, 'code' => '');
                } else {
                    $result = array('status' => false,'message' => 'Transaction Not Successful', 'code' => '');
                }
            } catch (Exception $e) {
                $result = array('status' => false,'message' => $e->getMessage(), 'code' => '');
            }
        }
        return json_encode($result);
    }

    public function subscribeForm()
    {

		$stripe = Stripe::make(env('STRIPE_API_KEY'));

		$plans = $stripe->plans()->all();

		$customer = $stripe->customers()->all();

    	return view('subscribe.index', compact('plans'));
    }

    public function subscribe(Request $request)
    {
    	 $data = $request->all();
        $validator = $this->validator($data, 'task3');

        if ($validator->fails())
        {           
            $result = array('status' => false, 'message' => $validator->errors()->first(), 'code' => '');
        }
        else
        {            
            $stripe = new Stripe(env('STRIPE_API_KEY'));
            try {
                $token = $stripe->tokens()->create([
                    'card' => [
                        'number'    => $request->get('card_no'),
                        'exp_month' => $request->get('ccExpiryMonth'),
                        'exp_year'  => $request->get('ccExpiryYear'),
                        'cvc'       => $request->get('cvvNumber'),
                    ],
                ]);
                if (!isset($token['id'])) {
                    $result = array('status' => false,'message' => 'The Stripe Token was not generated correctly', 'code' => '');
                }
                $customer = $stripe->customers()->create([
					'email' => $request->get('email'),
					'source'  => $token['id'],
				]);
				$start = Carbon::now()->startOfMonth();
				if(Carbon::now() != $start)
				{
					$charge = $stripe->charges()->create([
	                    'customer' => $customer['id'],
	                    'currency' => 'USD',
	                    'amount'   => $request->get('amount'),
	                    'description' => 'Add in wallet',
	                ]);
				}
                $subscription = $stripe->subscriptions()->create($customer['id'], [
				    'plan' => $request->get('plan'),
				    'trial_end' => Carbon::now()->lastOfMonth()->timestamp,
				]);
                if($subscription) {
                    /**
                    * Write Here Your Database insert logic.
                    */
                    $result = array('status' => false,'message' => 'Subscription Successful', 'code' => '');
                } else {
                    $result = array('status' => false,'message' => 'Subscription Not Successful', 'code' => '');
                }
            } catch (Exception $e) {
                $result = array('status' => false,'message' => $e->getMessage(), 'code' => '');
            } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
                $result = array('status' => false,'message' => $e->getMessage(), 'code' => '');
            } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                $result = array('status' => false,'message' => $e->getMessage(), 'code' => '');
            }
        }
        return json_encode($result);
    }
}
