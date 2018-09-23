<?php


class AuthCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    public function testAuthFailWithoutToken(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/test');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 401
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'Authentication has failed']);
    }

    public function testAuthFailWrongToken(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('token', 'wrongtoken');
        $I->sendGET('/test');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 401
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'Authentication has failed']);
    }
    
    public function testAuthSuccess(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('token', 'Qr7kK2qy1ICnlTO74RK4BtvCvCXOCVnRciYwqrN2YBqGoiHbgNWD35r9elf4');
        $I->sendGET('/test');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['name' => 'tim']);
    }
}
