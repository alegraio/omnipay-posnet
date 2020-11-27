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
        self::assertSame('https://setmpos.ykb.com/PosnetWebService/XML', $this->request->getEndpoint());
    }

    public function testData(): void
    {
        self::assertSame('517041', $this->request->getCcPrefix());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('MacValidationSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('https://setmpos.ykb.com/PosnetWebService/XML', $this->request->getEndpoint());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('MacValidationFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertEmpty($response->getTransactionReference());
        self::assertSame('https://setmpos.ykb.com/PosnetWebService/XML', $this->request->getEndpoint());
        self::assertSame('0600', $response->getErrorCode());
        self::assertSame('Mac Dogrulama hatali', $response->getMessage());
    }
}