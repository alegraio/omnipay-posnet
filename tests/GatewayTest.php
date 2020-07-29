<?php

namespace Omnipay\Tests;


use Omnipay\PosNet\Messages\PurchaseResponse;
use Omnipay\PosNet\Messages\RefundResponse;
use Omnipay\PosNet\PosNetGateway;

class GatewayTest extends GatewayTestCase
{
    /** @var PosNetGateway */
    public $gateway;

    /** @var array */
    public $options;
    /**
     * @var array
     */
    private $parameters;

    public function setUp()
    {
        /** @var PosNetGateway gateway */
        $this->gateway = new PosNetGateway(null, $this->getHttpRequest());
        $this->gateway->setMerchantId('6706598320');
        $this->gateway->setTerminalId('67005551');
        $this->gateway->setPosNetId('9644');
        $this->gateway->setXmlServiceUrl('https://setmpos.ykb.com/PosnetWebService/XML');
        $this->gateway->setCurrency('TL');
    }

    public function testPurchase(): void
    {
        $paymentCard = [
            'number' => '4506349116608409',
            'expiryMonth' => '03',
            'expiryYear' => '2024',
            'cvv' => '000'

        ];

        $this->parameters = [
            // 'mid' => $this->gateway->getMerchantId(),
            // 'tid' => $this->gateway->getTerminalId(),
            'tranDateRequired' => '1',
            'amount' => 2451,
            'orderID' => '1s3456z89012345678901234',
            'installment' => '02',
            'card' => $paymentCard
            // 'koiCode' => '',
            // 'subMrcId' => '',
            // 'tckn' => '',
            // 'vkn' => '',
            // 'subDealerCode' => '',
        ];
        /** @var PurchaseResponse $response */
        $response = $this->gateway->purchase($this->parameters)->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testCompleteAuthorize(): void
    {
        $this->assertTrue(true);
    }

    public function testRefund(): void
    {
        $this->parameters = [
            'tranDateRequired' => '1',
            'return' => [
                'amount' => 100,
                'currencyCode' => 'TL',
                'hostLogKey' => '019676067890000191'
            ]
        ];

        /** @var RefundResponse $response */
        $response = $this->gateway->refund($this->parameters)->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testOrderTransaction(): void
    {
        $this->gateway->orderTransaction();
        $this->assertTrue(true);
    }
}
