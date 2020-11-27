<?php

namespace OmnipayTest\PosNet\Messages;

use Omnipay\PosNet\Messages\PurchaseRequest;

class Purchase3dRequestTest extends PosNetTestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getPurchase3dParams());
    }

    public function testOrderId(): void
    {
        self::assertSame('', $this->request->getOrderId());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('Purchase3dSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertTrue($response->isRedirect());
        self::assertSame('', $response->getRedirectData()['orderid']);
        self::assertSame('https://setmpos.ykb.com/3DSWebService/YKBPaymentService', $response->getRedirectUrl());
    }
}