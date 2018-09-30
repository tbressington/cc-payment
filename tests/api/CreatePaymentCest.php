<?php

class CreatePaymentCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    /**
     * Using the wrong method (GET instead of POST)
     * returns the correct response code and  
     * error message in JSON.
     */
    public function testCreatePaymentFailWrongMethod(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('token', 'Qr7kK2qy1ICnlTO74RK4BtvCvCXOCVnRciYwqrN2YBqGoiHbgNWD35r9elf4');
        $I->sendGET('/createPayment');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'Method not allowed']);
    }

    /**
     * Passing no parameters returns a 200 status,
     * code and error message in JSON.
     */
    public function testCreatePaymentFailNoParams(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('token', 'Qr7kK2qy1ICnlTO74RK4BtvCvCXOCVnRciYwqrN2YBqGoiHbgNWD35r9elf4');
        $I->sendPOST('/createPayment');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'Parameter validation failed. The card number field is required. The card exp month field is required. The card exp year field is required. The card cvc field is required. The customer name field is required. The tx amount field is required. The tx description field is required.']);
    }

    /**
     * Passing an incorrectly named 'card_cvc1' parameter returns,
     * a 200 status code and error message in JSON.
     */
    public function testCreatePaymentFailInvalidCardCVC1Param(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('token', 'Qr7kK2qy1ICnlTO74RK4BtvCvCXOCVnRciYwqrN2YBqGoiHbgNWD35r9elf4');
        $I->sendPOST('/createPayment', [
            'card_number'    => '4000000000000069',
            'card_exp_month' => '01',
            'card_exp_year'  => '2019',
            'card_cvc1'      => '123',
            'customer_name'  => 'John Doe',
            'tx_amount'      => 10.99,
            'tx_currency'    => 'gbp',
            'tx_description' => 'Codeception test payment',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'Parameter validation failed. The card cvc field is required.']);
    }

    /**
     * Passing an incorrectly named 'card_cvc1' parameter returns,
     * a 200 status code and error message in JSON.
     */
    public function testCreatePaymentFailInvalidCardNumbersParam(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('token', 'Qr7kK2qy1ICnlTO74RK4BtvCvCXOCVnRciYwqrN2YBqGoiHbgNWD35r9elf4');
        $I->sendPOST('/createPayment', [
            'card_numbers'   => '4000000000000069',
            'card_exp_month' => '01',
            'card_exp_year'  => '2019',
            'card_cvc'       => '123',
            'customer_name'  => 'John Doe',
            'tx_amount'      => 10.99,
            'tx_currency'    => 'gbp',
            'tx_description' => 'Codeception test payment',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'Parameter validation failed. The card number field is required.']);
    }

    /**
     * Using a card which has expired returns a 200
     * status and the correct error message
     * in JSON.
     */
    public function testCreatePaymentFailedExpiredCard(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('token', 'Qr7kK2qy1ICnlTO74RK4BtvCvCXOCVnRciYwqrN2YBqGoiHbgNWD35r9elf4');
        $I->sendPOST('/createPayment', [
            'card_number'    => '4000000000000069',
            'card_exp_month' => '01',
            'card_exp_year'  => '2019',
            'card_cvc'       => '123',
            'customer_name'  => 'John Doe',
            'tx_amount'      => 10.99,
            'tx_currency'    => 'gbp',
            'tx_description' => 'Codeception test payment',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'Your card has expired.']);
    }
    
    /**
     * Using a valid card returns a status code 
     * 200 and a success value of TRUE.
     */
    public function testCreatePaymentSuccess(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('token', 'Qr7kK2qy1ICnlTO74RK4BtvCvCXOCVnRciYwqrN2YBqGoiHbgNWD35r9elf4');
        $I->sendPOST('/createPayment', [
            'card_number'    => '4000000000000077',
            'card_exp_month' => '01',
            'card_exp_year'  => '2019',
            'card_cvc'       => '123',
            'customer_name'  => 'John Doe',
            'tx_amount'      => 10.99,
            'tx_currency'    => 'gbp',
            'tx_description' => 'Codeception test payment',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['success' => true]);
    }
}
