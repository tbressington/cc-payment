<?php

namespace App\Repositories\PaymentGateway;

use \Stripe\Stripe;

class StripeRepository implements PaymentGatewayRepository
{
    public function createPayment($request)
    {
        $tx_amount = $request->get('tx_amount');
        $tx_amount = $this->convertToInt($tx_amount);
        
        Stripe::setApiKey(config('payment.api_keys.stripe'));

        try {
            $token = \Stripe\Token::create(array(
                'card' => array(
                    'number'    => $request->get('card_number'),
                    'exp_month' => $request->get('card_exp_month'),
                    'exp_year'  => $request->get('card_exp_year'),
                    'cvc'       => $request->get('card_cvc'),
                    'name'      => $request->get('customer_name')
                )
            ));
        } catch (\Exception $e) {
            abort('500');
        }

        if (!isset($token->id)) {
            abort('404', 'Token not found');
        }

        $charge = \Stripe\Charge::create([
            'amount' => $tx_amount,
            'currency' => $request->get('tx_currency') ? $request->get('tx_currency') : 'gbp',
            'description' => $request->get('tx_description'),
            'source' => $token->id
        ]);

        return $charge;
    }

    protected function convertToInt($amount)
    {
        if (is_numeric($amount)) {
            return is_int($amount) ? $amount : intval($amount * 100);
        }

        abort('500', 'Transaction amount must be a number');
    }
}