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

    public function testOosTdsServiceUrl(): void
    {
        self::assertSame('https://setmpos.ykb.com/3DSWebService/YKBPaymentService', $this->request->getOosTdsServiceUrl());
    }

    public function testXId(): void
    {
        self::assertSame('12345678901234567890', $this->request->getXid());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('Purchase3dSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertTrue($response->isRedirect());
        self::assertNotEmpty($response->getHtml());
        self::assertSame('https://setmpos.ykb.com/3DSWebService/YKBPaymentService', $response->getRedirectData()['oosTdsServiceUrl']);
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('Purchase3dFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertEmpty($response->getRedirectData());
        self::assertSame('https://setmpos.ykb.com/3DSWebService/YKBPaymentService', $response->getRedirectUrl());
    }
}