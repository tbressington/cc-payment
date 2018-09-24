<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Stripe\Stripe;

class APIController extends Controller
{
    public function test()
    {
        return response()->json([
            'name' => 'tim'
        ]);
    }

    public function makePayment()
    {
        // Test card details
        //   Card No:   4000000000000077
        //   Card Type: Mastercard (debit)
        
        Stripe::setApiKey(config('payment.api_keys.stripe'));

        try {
            $token = \Stripe\Token::create(array(
                'card' => array(
                    'number'    => '4000000000000077', // 4000000000009995 -> insufficient funds
                    'exp_month' => '01',
                    'exp_year'  => '2019',
                    'cvc'       => '123',
                    'name'      => 'John Doe'
                )
            ));
        } catch (\Exception $e) {
            abort('500');
        }

        if (!isset($token->id)) {
            abort('404', 'Token not found');
        }

        $charge = \Stripe\Charge::create([
            'amount' => 1099,
            'currency' => 'gbp',
            'description' => 'Test payment',
            'source' => $token->id
        ]);

        dd($charge);
    }
}
