<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PaymentGateway\PaymentGatewayRepository;

class APIController extends Controller
{
    protected $payment_gateway;

    public function __construct(PaymentGatewayRepository $payment_gateway)
    {
        $this->payment_gateway = $payment_gateway;
    }

    public function createPayment(Request $request)
    {
        $validated_data = $request->validate([
            'card_number'    => 'required',
            'card_exp_month' => 'required|dateformat:m',
            'card_exp_year'  => 'required|dateformat:Y',
            'card_cvc'       => 'required|numeric',
            'customer_name'  => 'required|string',
            'tx_amount'      => 'required|numeric',
            'tx_currency'    => 'string',
            'tx_description' => 'required|string',
        ]);
        
        $charge = $this->payment_gateway->createPayment($request);

        dd($charge);
    }
}
