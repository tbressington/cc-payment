<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Repositories\PaymentGateway\PaymentGatewayRepository;

class APIController extends Controller
{
    protected $payment_gateway;

    public function __construct(PaymentGatewayRepository $payment_gateway)
    {
        $this->payment_gateway = $payment_gateway;
    }

    public function index()
    {
        return response()->json([
            'welcome' => 'api',
        ]);
    }

    public function createPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_number'    => 'required',
            'card_exp_month' => 'required|dateformat:m',
            'card_exp_year'  => 'required|dateformat:Y',
            'card_cvc'       => 'required|numeric',
            'customer_name'  => 'required|string',
            'tx_amount'      => 'required|numeric',
            'tx_currency'    => 'string',
            'tx_description' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = [];
            $validator_errors = $validator->errors();
            foreach ($validator_errors->all() as $message) {
                $errors[] = $message;
            }
            
            return response()->json([
                'error' => 'Parameter validation failed. '.implode(' ', $errors),
            ]);
        }
        
        try {
            $payment = $this->payment_gateway->createPayment($request);

            if (isset($payment->status) && $payment->status == 'succeeded') {
                return response()->json([
                    'success' => true
                ]);
            }
        } catch (\Stripe\Error\Card $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            return response()->json([
                'error' => $err['message']
            ]);
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $body = $e->getJsonBody();
            $err  = $body['error'];
            return response()->json([
                'error' => $err['message']
            ]);
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $body = $e->getJsonBody();
            $err  = $body['error'];
            return response()->json([
                'error' => $err['message']
            ]);
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $body = $e->getJsonBody();
            $err  = $body['error'];
            return response()->json([
                'error' => $err['message']
            ]);
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $body = $e->getJsonBody();
            $err  = $body['error'];
            return response()->json([
                'error' => $err['message']
            ]);
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $body = $e->getJsonBody();
            $err  = $body['error'];
            return response()->json([
                'error' => $err['message']
            ]);
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $body = $e->getJsonBody();
            $err  = $body['error'];
            return response()->json([
                'error' => $err['message']
            ]);
        }
    }
}
