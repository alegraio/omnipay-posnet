<?php

namespace OmnipayTest\PosNet\Messages;

use Omnipay\PosNet\Messages\CompletePurchaseRequest;

class CompletePurchaseRequestTest extends PosNetTestCase
{
    /**
     * @var CompletePurchaseRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getCompletePurchaseParams());
    }

    public function testEndpoint(): void
    {
        self::assertSame('https://setmpos.ykb.com/PosnetWebService/XML', $this->request->getEndpoint());
    }

    public function testSign(): void
    {
        self::assertSame('7D12250A1313A3E7C71192371EFC71F2', $this->request->getSign());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('CompletePurchaseSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertNotEmpty($response->getServiceRequestParams());
        self::assertSame('https://setmpos.ykb.com/PosnetWebService/XML', $this->request->getEndpoint());
        self::assertSame('031141873090000201', $response->getTransactionReference());
        self::assertSame('418730', $response->getCode());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('CompletePurchaseFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertEmpty($response->getTransactionReference());
        self::assertSame('https://setmpos.ykb.com/PosnetWebService/XML', $this->request->getEndpoint());
        self::assertSame('0127', $response->getErrorCode());
        self::assertSame('ORDERID DAHA ONCE KULLANILMIS      0127', $response->getMessage());
    }
}