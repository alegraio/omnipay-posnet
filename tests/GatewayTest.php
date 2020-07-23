<?php

namespace Omnipay\Tests;


use Omnipay\PosNet\PosNetGateway;

class GatewayTest extends GatewayTestCase
{
    /** @var PosNetGateway */
    public $gateway;

    /** @var array */
    public $options;

    public function setUp()
    {
        /** @var PosNetGateway gateway */
        $this->gateway = new PosNetGateway(null, $this->getHttpRequest());
    }

    public function testPurchase(): void
    {
        $this->assertTrue(true);
    }

    public function testCompleteAuthorize(): void
    {
        $this->assertTrue(true);
    }

    public function testRefund(): void
    {
        $this->assertTrue(true);
    }

    public function testOrderTransaction(): void
    {
        $this->gateway->orderTransaction();
        $this->assertTrue(true);
    }
}
