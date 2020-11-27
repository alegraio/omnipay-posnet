<?php

namespace OmnipayTest\PosNet\Messages;

use Omnipay\PosNet\Messages\RefundRequest;

class RefundRequestTest extends PosNetTestCase
{
    /**
     * @var RefundRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getRefundParams());
    }

    public function testEndpoint(): void
    {
        self::assertSame('https://setmpos.ykb.com/PosnetWebService/XML', $this->request->getEndpoint());
    }

    public function testHostLogKey(): void
    {
        self::assertSame('031141836890000201', $this->request->getHostLogKey());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('RefundSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('https://setmpos.ykb.com/PosnetWebService/XML', $this->request->getEndpoint());
        self::assertSame('019799179990000191', $response->getTransactionReference());
        self::assertSame('991799', $response->getCode());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('RefundFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertSame('031141836890000201', $response->getTransactionReference());
        self::assertSame('https://setmpos.ykb.com/PosnetWebService/XML', $this->request->getEndpoint());
        self::assertSame('753497', $response->getCode());
        self::assertSame('0205', $response->getErrorCode());
        self::assertSame('GECERSIZ TUTAR                     0205', $response->getMessage());
    }
}