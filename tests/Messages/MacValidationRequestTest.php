<?php

namespace OmnipayTest\PosNet\Messages;

use Omnipay\PosNet\Messages\MacValidationRequest;

class MacValidationRequestTest extends PosNetTestCase
{
    /**
     * @var MacValidationRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new MacValidationRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getMacValidationParams());
    }

    public function testEndpoint(): void
    {
        self::assertSame('https://setmpos.ykb.com/3DSWebService/YKBPaymentService', $this->request->getEndpoint());
    }

    public function testOrderId(): void
    {
        self::assertSame('181683681', $this->request->getOrderId());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('MacValidationSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('https://setmpos.ykb.com/3DSWebService/YKBPaymentService', $this->request->getEndpoint());
        self::assertSame('', $response->getTransactionReference());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('MacValidationSuccess.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('', $response->getTransactionReference());
        self::assertSame('https://setmpos.ykb.com/3DSWebService/YKBPaymentService', $this->request->getEndpoint());
        self::assertSame('', $response->getCode());
        self::assertSame('', $response->getMessage());
    }
}