<?php

namespace OmnipayTest\PosNet;


use Omnipay\PosNet\Messages\CompletePurchaseRequest;
use Omnipay\PosNet\Messages\HelperTrait;
use Omnipay\PosNet\Messages\MacValidationException;
use Omnipay\PosNet\Messages\PurchaseRequest;
use Omnipay\PosNet\Messages\RefundRequest;
use Omnipay\PosNet\Messages\VoidRequest;
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
        $this->gateway->setOosTdsServiceUrl('https://setmpos.ykb.com/3DSWebService/YKBPaymentService');
        $this->gateway->setEncKey('10,10,10,10,10,10,10,10');
        $this->gateway->setTestMode(true);
        $this->gateway->setCurrency('TRY');
    }

    public function testPurchase3D(): void
    {
        $paymentCard = [
            'number' => '5400617024479160',
            'expiryMonth' => '11',
            'expiryYear' => '2024',
            'cvv' => '000',
            'billingFirstName' => 'john',
            'billingLastName' => 'doe'

        ];

        $this->parameters = [
            // 'mid' => $this->gateway->getMerchantId(),
            // 'tid' => $this->gateway->getTerminalId(),
            'tranType' => 'Sale',
            'amount' => 100.00,
            // 'orderID' => 'test-0200000000000000000',
            'xid' => 'YKB_COMP_TEST4567890', // Must has size of 20 characters exactly
            'installment' => '00',
            'card' => $paymentCard,
            'merchantReturnUrl' => 'http://test.domain.com/payment',
            'websiteUrl' => 'https://omnipay.com',
            'paymentMethod' => '3d' // '3d', 'direct'
        ];
        /** @var PurchaseRequest $request */
        $request = $this->gateway->purchase($this->parameters);
        self::assertInstanceOf(PurchaseRequest::class, $request);
        self::assertSame('YKB_COMP_TEST4567890', $request->getXid());
        /*
        // @var Purchase3DResponse $response
        $response = $this->gateway->purchase($this->parameters)->send();
        var_dump($response->getRedirectData());
        var_dump($response->getHtml());
        if ($response->isSuccessful()) {
            self::assertNotEmpty($response->getHtml());
        }
        self::assertTrue($response->isSuccessful());*/
    }

    public function testPurchase(): void
    {
        $paymentCard = [
            'number' => '5400617024479160',
            'expiryMonth' => '11',
            'expiryYear' => '2024',
            'cvv' => '00',
            'billingFirstName' => 'john',
            'billingLastName' => 'doe'

        ];

        $this->parameters = [
            // 'mid' => $this->gateway->getMerchantId(),
            // 'tid' => $this->gateway->getTerminalId(),
            'tranType' => 'Sale',
            'tranDateRequired' => '1',
            'amount' => 100.00,
            'orderID' => 'test-0100000000000000000',
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
        /** @var PurchaseRequest $request */
        $request = $this->gateway->purchase($this->parameters);
        self::assertInstanceOf(PurchaseRequest::class, $request);
        self::assertSame('test-0100000000000000000', $request->getOrderID());
        /*
        // @var PurchaseResponse $response
        $response = $this->gateway->purchase($this->parameters)->send();
        var_dump($response->getTransactionReference());
        self::assertTrue($response->isSuccessful());*/
    }

    /**
     * @throws MacValidationException
     */
    public function testCompletePurchase(): void
    {
        $this->parameters = [
            'merchantPacket' => '28A279A58EB3606E5CC5A7931216416DB1C8B314B73A2287D1B6E98403B0D5EBBD618869E453E5C2975B0288326FC3E6F0A311E1A1DBC8F9A6685DB24B990C62F27D1792C4518922A0695AAF5541D72C0DCB2A2F68472972F697E90459421BDEA5F41C8711343D1F366102FD2699C7F0F600508DA242F8629D0AE5661492EAA7F929615DE93CB71501BC15B4359BEA30F9C0E8062746670C02351251902F5C0ED98DA576589F183322B35E6B5CB5BBD1F1207FD9A99BF83C5E27EA6DB19B76238F46737280DAFBA68CFB5390',
            // merchantData
            'bankPacket' => '95E656A1A17DFA4139048DFFD2F855BC106F06F2B330F2006507E11F517FD0A313B9D49FF3CAE3FEB251B74B8171C6156EF9B0FC8AFBE77F8FC3C6296F38C2FFEE496583BB7B33252B0A1B78B31B7C99D6A99B8A752B93AC8102091C025729C8BF0C57AFDF73AB51A95F0F5B3D6DB125014F261CBD6171D0C9631ABC41A3686D0D847223487356AD08AABCA2',
            // bankData
            'sign' => 'FF9151DD5D217B8D9CA128D3134DDCBB',
            'ccPrefix' => '540061',
            'tranType' => 'Sale',
            'amount' => 10000,
            'wpAmount' => 0,
            'xid' => 'YKB_COMP_TEST4567890',
        ];
        /** @var CompletePurchaseRequest $request */
        $request = $this->gateway->completePurchase($this->parameters);
        self::assertInstanceOf(CompletePurchaseRequest::class, $request);
        self::assertSame('FF9151DD5D217B8D9CA128D3134DDCBB', $request->getSign());
        self::assertSame('YKB_COMP_TEST4567890', $request->getXid());
        /*
        // @var CompletePurchaseResponse $response
        try {
            $response = $this->gateway->completePurchase($this->parameters)->send();
        } catch (MacValidationException $e) {
            self::assertTrue(false);
        }
        self::assertTrue($response->isSuccessful());*/
    }

    public function testRefund(): void
    {
        $this->parameters = [
            'tranDateRequired' => '1',
            'amount' => 20.00,
            'hostLogKey' => '031141836890000201'
        ];

        /** @var RefundRequest $request */
        $request = $this->gateway->refund($this->parameters);
        self::assertInstanceOf(RefundRequest::class, $request);
        self::assertSame('031141836890000201', $request->getHostLogKey());
        /*
        // @var RefundResponse $response
        $response = $this->gateway->refund($this->parameters)->send();
        self::assertTrue($response->isSuccessful()); */
    }

    public function testVoid(): void
    {
        $this->parameters = [
            'transaction' => 'sale',
            'hostLogKey' => '031141844690000201'
        ];

        /** @var VoidRequest $request */
        $request = $this->gateway->void($this->parameters);
        self::assertInstanceOf(VoidRequest::class, $request);
        self::assertSame('031141844690000201', $request->getHostLogKey());

        /*
        // @var VoidResponse $response
        $response = $this->gateway->void($this->parameters)->send();
        var_dump($response->getTransactionReference());
        var_dump($response->getCode());
        self::assertTrue($response->isSuccessful()); */
    }

    public function testBaseParameters(): void
    {
        /** @var PurchaseRequest $request */
        $request = $this->gateway->purchase([]);
        $request->setOrderId('xxxxx');
        $request->setXid('xxxxx');
        $request->setInstallment('xxxxx');
        $request->setWpAmount('xxxxx');
        $request->setMerchantPacket('xxxxx');
        $request->setBankPacket('xxxxx');
        $request->setSign('xxxxx');
        $request->setCcPrefix('xxxxx');
        $request->setTranType('xxxxx');
        $request->setTranDateRequired('xxxxx');
        $request->setHostLogKey('xxxxx');
        $request->setMerchantReturnUrl('xxxxx');
        $request->setWebsiteUrl('xxxxx');
        $request->setTransaction('xxxxx');
        $request->setAuthCode('xxxxx');

    }
}
