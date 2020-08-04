<?php

namespace Omnipay\Tests;


use Omnipay\PosNet\Messages\AuthorizeResponse;
use Omnipay\PosNet\Messages\HelperTrait;
use Omnipay\PosNet\Messages\PurchaseResponse;
use Omnipay\PosNet\Messages\RefundResponse;
use Omnipay\PosNet\PosNetGateway;

class GatewayTest extends GatewayTestCase
{
    use HelperTrait;

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
        $this->gateway->setMerchantId(getenv('MERCHANT_ID'));
        $this->gateway->setTerminalId(getenv('TERMINAL_ID'));
        $this->gateway->setPosNetId(getenv('POSNET_ID'));
        $this->gateway->setTestMode(true);
        $this->gateway->setCurrency('TL');
    }

    public function testAuthorize(): void
    {
        $paymentCard = [
            'number' => '4506349116608409',
            'expiryMonth' => '03',
            'expiryYear' => '2024',
            'cvv' => '000',
            'billingFirstName' => 'john',
            'billingLastName' => 'doe'

        ];

        $this->parameters = [
            // 'mid' => $this->gateway->getMerchantId(),
            // 'tid' => $this->gateway->getTerminalId(),
            'tranType' => 'Sale',
            'amount' => 3600,
            'orderID' => 'YKB_0000080603143050',
            'installment' => '00',
            'card' => $paymentCard
        ];
        /** @var AuthorizeResponse $response */
        $response = $this->gateway->authorize($this->parameters)->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testPurchase(): void
    {
        $paymentCard = [
            'number' => '5170410000000004',
            'expiryMonth' => '12',
            'expiryYear' => '2030',
            'cvv' => '123'

        ];

        $this->parameters = [
            // 'mid' => $this->gateway->getMerchantId(),
            // 'tid' => $this->gateway->getTerminalId(),
            'tranDateRequired' => '1',
            'amount' => 2451,
            'orderID' => $this->getRandomString(24),
            'installment' => '00',
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
            'amount' => 2000,
            'hostLogKey' => '026961487790000201'
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
