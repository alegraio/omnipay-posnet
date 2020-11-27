<?php

namespace OmnipayTest\PosNet\Messages;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class PosNetTestCase extends TestCase
{
    /**
     * @return array
     */
    protected function getMacValidationParams(): array
    {
        $params = [
            'merchantPacket' => '0B37851A9D3F5659C9684BEE6D092454D89D510A66DA3EA0EFCADE1F7340402D2BDD78F28F296EC7A762FB01FF9A9E62A3CF471C1C06EA2930EA34398CA7B85A898ABE7A146C9C77F4B94EEDE250DBDBF707789DF9A7C316AFDE583803002D383EF7932819BFF89EA704A5B38CC9BB1E72C19272CCBE994ADD81D8E0FAB23ED868105B4CE5B488736E1871288E88176A0A7074A91DCED3B3449D009EF5D26EB320B362C2FC281861EEF37DB0BCA4C19F4D3C2D47B9EEC88D82AB9628265F1545A407051B804004A3D85F29A6',
            // merchantData
            'bankPacket' => '6E6A835099604767C9D846898D270902B2954676FCE568BD25C7CF0A7152208DF8A34A3D3C71DD284BF9EA89A17DE6C9510272B68DEF0E9D0B01E4539B72D17E764C63F901457E4904235C4CF1D55E7840927C37BEA07169FF4CF4DCD46804FB1DFD61F93C8AB50B20EACE43C1AC4CA58AD7AA55CEBEE7017A7F08B3478CF13AF113A11401A8C5CE0A315FC7',
            // bankData
            'sign' => '8A456B8923C8E5E85C890E6A861005B2',
            'ccPrefix' => '517041',
            'tranType' => 'Sale',
            'amount' => 10000,
            'wpAmount' => 0,
            'xid' => '12345678901234567890',
        ];

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     */
    protected function getPurchaseParams(): array
    {
        $params = $this->getDefaultPurchaseParams();

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     */
    protected function getPurchase3dParams(): array
    {
        $params = $this->getDefaultPurchaseParams();
        $params['paymentMethod'] = '3d';
        $params['xid'] = '12345678901234567890';

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     */
    protected function getCompletePurchaseParams(): array
    {
        $params = [
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
        ];

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     */
    protected function getRefundParams(): array
    {
        $params = [
            'tranDateRequired' => '1',
            'amount' => 120.00,
            'hostLogKey' => '031141836890000201'
        ];

        return $this->provideMergedParams($params);
    }

    protected function getVoidParams(): array
    {
        $params = [
            'transaction' => 'sale',
            'hostLogKey' => '031141836890000201'
        ];

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     */
    private function getDefaultOptions(): array
    {
        return [
            'testMode' => true,
            'terminalId' => '67537267',
            'merchantId' => '6797752273',
            'posNetId' => '28440',
            'encKey' => '10,10,10,10,10,10,10,10',
            'oosTdsServiceUrl' => 'https://setmpos.ykb.com/3DSWebService/YKBPaymentService'
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    private function provideMergedParams(array $params): array
    {
        $params = array_merge($params, $this->getDefaultOptions());
        return $params;
    }

    /**
     * @return array
     */
    protected function getDefaultPurchaseParams(): array
    {
        return [
            'card' => $this->getCardInfo(),
            'tranType' => 'Sale',
            'tranDateRequired' => '1',
            'amount' => 100.00,
            'orderID' => '000012345678901234567890', // orderId must be 24 characters
            'installment' => '00',
            'merchantReturnUrl' => 'https://posnet.omnipay.com/payment',
            'websiteUrl' => 'https://omnipay.com'
        ];
    }

    /**
     * @return CreditCard
     */
    private function getCardInfo(): CreditCard
    {
        $cardInfo = $this->getValidCard();
        $cardInfo['number'] = '5170410000000004';
        $cardInfo['expiryMonth'] = '12';
        $cardInfo['expiryYear'] = '2030';
        $cardInfo['cvv'] = '123';
        $card = new CreditCard($cardInfo);
        $card->setEmail('test@yapikredi.com.tr');
        $card->setFirstName('john');
        $card->setLastName('doe');

        return $card;
    }
}