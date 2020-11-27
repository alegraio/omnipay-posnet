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
        $this->request->initialize($this->getVoidParams());
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
        $this->setMockHttpResponse('VoidSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertSame('https://setmpos.ykb.com/PosnetWebService/XML', $this->request->getEndpoint());
        self::assertSame('031141844690000201', $response->getTransactionReference());
        self::assertSame('000000', $response->getCode());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('VoidFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertEmpty($response->getTransactionReference());
        self::assertSame('https://setmpos.ykb.com/PosnetWebService/XML', $this->request->getEndpoint());
        self::assertSame('0220', $response->getErrorCode());
        self::assertSame('IPTAL ISLEMI YAPILMIS              0220', $response->getMessage());
    }
}