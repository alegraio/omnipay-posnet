<?php

namespace OmnipayTest\PosNet;


use Omnipay\PosNet\Messages\Purchase3DResponse;
use Omnipay\PosNet\Messages\CompletePurchaseResponse;
use Omnipay\PosNet\Messages\HelperTrait;
use Omnipay\PosNet\Messages\MacValidationException;
use Omnipay\PosNet\Messages\PurchaseResponse;
use Omnipay\PosNet\Messages\RefundResponse;
use Omnipay\PosNet\Messages\VoidResponse;
use Omnipay\PosNet\PosNetGateway;
use Omnipay\Tests\GatewayTestCase;

class PosNetGatewayTest extends GatewayTestCase
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
        $this->gateway->setMerchantId('6797752273');
        $this->gateway->setTerminalId('67537267');
        $this->gateway->setPosNetId('28440');
        $this->gateway->setOosTdsServiceUrl('https://setmpos.ykb.com/PosnetWebService/XML');
        $this->gateway->setEncKey('10,10,10,10,10,10,10,10');
        $this->gateway->setTestMode(true);
        $this->gateway->setCurrency('TRY');
    }

    public function testPurchase3D(): void
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
            'amount' => 36.00,
            'orderID' => 'YKB_0000080603143050',
            'installment' => '00',
            'card' => $paymentCard,
            'merchantReturnUrl' => 'https://posnet.omnipay.com/payment',
            'websiteUrl' => 'https://omnipay.com',
            'paymentType' => '3d' // '3d', 'direct'
        ];
        /** @var Purchase3DResponse $response */
        $response = $this->gateway->purchase($this->parameters)->send();
        if ($response->isSuccessful()) {
            self::assertNotEmpty($response->getHtml());
        }
        self::assertTrue($response->isSuccessful());
    }

    public function testPurchase(): void
    {
        $paymentCard = [
            'number' => '5170410000000004',
            'expiryMonth' => '12',
            'expiryYear' => '2030',
            'cvv' => '123',
            'billingFirstName' => 'john',
            'billingLastName' => 'doe'

        ];

        $this->parameters = [
            // 'mid' => $this->gateway->getMerchantId(),
            // 'tid' => $this->gateway->getTerminalId(),
            'tranType' => 'Sale',
            'tranDateRequired' => '1',
            'amount' => 100.00,
            'orderID' => '000012345678901234567890',
            'installment' => '00',
            'card' => $paymentCard,
            'merchantReturnUrl' => 'https://posnet.omnipay.com/payment',
            'websiteUrl' => 'https://omnipay.com',
            'paymentMethod' => 'direct' // '3d', 'direct'
            // 'koiCode' => '',
            // 'subMrcId' => '',
            // 'tckn' => '',
            // 'vkn' => '',
            // 'subDealerCode' => '',
        ];
        /** @var PurchaseResponse $response */
        $response = $this->gateway->purchase($this->parameters)->send();
        var_dump($response->getTransactionReference());
        self::assertTrue($response->isSuccessful());
    }

    public function testCompletePurchase(): void
    {
        $this->parameters = [
            // 'mid' => $this->gateway->getMerchantId(),
            // 'tid' => $this->gateway->getTerminalId(),
            'merchantPacket' => '1B0C0D5DC36A7F2286AC08D90ECD920419D6D346DFEE4015E173D533DE4208BB5AEB62478E0B462731AA7BECADD84D37E76B306DDD4088E0C6B843D89529E6A74B2B07514C71D72ABC4F5F2EBF560C7829336CD079AFE2A2A24ABD822DBB6627FF10DF1B245889216D352A8486F685E336F9E99D321C47BF449C6465307B2B31D8DEF4F2647E582D1BF2E2737E248558DC0CFF3C9B892426B494ACD56BD62D49366B3B85FCDDE8F073A791E0D9EA784F3B8AF6E57B80712B7A560C03C102678C0E5DB94F76017D9AB13F5549',
            // merchantData
            'bankPacket' => '8DFEA7222BC487C8370F76B1EF7F2443A86626248BBCB3FB332EB15A2A561896B265580BC4EEE3551F93628EE22842614680689B4D376444C196084A0B2D02C9E77383B243AF1F22A1487DA4874A3B38A048B00F0D09575FE5357621649D693A2AFB68444148DDA096437C6A37042A6577152D5C5014098D7C42970AFFA6F474108B89AA8708AFF47A2265A0',
            // bankData
            'sign' => '7D12250A1313A3E7C71192371EFC71F2',
            'ccPrefix' => '517041',
            'tranType' => 'Sale',
            'amount' => 24.50,
            'wpAmount' => 0,
            'xid' => 'YpJkEnAN90rHzMDFaGjI',
            // 'merchantId' => 'XXXXXXXX' // merchantId is already defined in Gateway object
        ];
        /** @var CompletePurchaseResponse $response */
        try {
            $response = $this->gateway->completePurchase($this->parameters)->send();
        } catch (MacValidationException $e) {
            self::assertTrue(false);
        }
        self::assertTrue($response->isSuccessful());
    }

    public function testRefund(): void
    {
        $this->parameters = [
            'tranDateRequired' => '1',
            'amount' => 20.00,
            'hostLogKey' => '026961487790000201'
        ];

        /** @var RefundResponse $response */
        $response = $this->gateway->refund($this->parameters)->send();
        self::assertTrue($response->isSuccessful());
    }

    public function testVoid(): void
    {
        $this->parameters = [
            'transaction' => 'sale',
            'hostLogKey' => '026963775690000201'
        ];

        /** @var VoidResponse $response */
        $response = $this->gateway->void($this->parameters)->send();
        self::assertTrue($response->isSuccessful());
    }
}
