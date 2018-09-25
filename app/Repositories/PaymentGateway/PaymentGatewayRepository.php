<?php

namespace App\Repositories\PaymentGateway;

interface PaymentGatewayRepository
{
    public function createPayment($request);
}