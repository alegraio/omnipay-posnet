<?php

namespace OmnipayTest\PosNet\Messages;

use Omnipay\PosNet\Messages\PurchaseRequest;

class PurchaseRequestTest extends PosNetTestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getPurchaseParams());
    }

    public function testEndpoint(): void
    {
        self::assertSame('https://setmpos.ykb.com/PosnetWebService/XML', $this->request->getEndpoint());
    }

    public function testOrderId(): void
    {
        self::assertSame('000012345678901234567890', $this->request->getOrderId());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('https://setmpos.ykb.com/PosnetWebService/XML', $this->request->getEndpoint());
        self::assertSame('031141836890000201', $response->getTransactionReference());
        self::assertSame('753497', $response->getCode());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertNull($response->getTransactionReference());
        self::assertSame('https://setmpos.ykb.com/PosnetWebService/XML', $this->request->getEndpoint());
        self::assertEmpty($response->getCode());
        self::assertSame('148 MID,TID,IP HATALI: 176.88.xxx.xx', $response->getMessage());
    }
}