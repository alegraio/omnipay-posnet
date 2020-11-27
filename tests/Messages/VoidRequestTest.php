<?php

namespace OmnipayTest\PosNet\Messages;

use Omnipay\PosNet\Messages\VoidRequest;

class VoidRequestTest extends PosNetTestCase
{
    /**
     * @var VoidRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getRefundParams());
    }

    public function testEndpoint(): void
    {
        self::assertSame('https://setmpos.ykb.com/3DSWebService/YKBPaymentService', $this->request->getEndpoint());
    }

    public function testOrderId(): void
    {
        self::assertSame('', $this->request->getOrderId());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('VoidSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertTrue($response->isCancelled());
        self::assertSame('https://setmpos.ykb.com/3DSWebService/YKBPaymentService', $this->request->getEndpoint());
        self::assertSame('', $response->getTransactionReference());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('VoidFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isCancelled());
        self::assertSame('', $response->getTransactionReference());
        self::assertSame('https://setmpos.ykb.com/3DSWebService/YKBPaymentService', $this->request->getEndpoint());
        self::assertSame('', $response->getCode());
        self::assertSame('', $response->getMessage());
    }
}