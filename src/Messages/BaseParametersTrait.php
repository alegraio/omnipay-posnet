<?php


namespace Omnipay\PosNet\Messages;


use Omnipay\Common\Exception\InvalidRequestException;

trait BaseParametersTrait
{
    public $xmlServiceUrls = [
        'test' => 'https://setmpos.ykb.com/PosnetWebService/XML',
        'live' => 'https://www.posnet.ykb.com/PosnetWebService/XML',
        'test3d' => 'https://setmpos.ykb.com/3DSWebService/YKBPaymentService',
        '3d' => 'https://www.posnet.ykb.com/3DSWebService/YKBPaymentService'
    ];
    public $encKey = '10,10,10,10,10,10,10,10';

    public function setMerchantId(string $merchantId)
    {
        return $this->setParameter('merchantId', $merchantId);
    }

    public function getMerchantId(): string
    {
        return $this->getParameter('merchantId');
    }

    public function setTerminalId(string $terminalId)
    {
        return $this->setParameter('terminalId', $terminalId);
    }

    public function getTerminalId(): string
    {
        return $this->getParameter('terminalId');
    }

    public function setPosNetId(string $posNetId)
    {
        return $this->setParameter('posNetId', $posNetId);
    }

    public function getPosNetId(): string
    {
        return $this->getParameter('posNetId');
    }


    public function setEncKey(string $encKey)
    {
        return $this->setParameter('encKey', $encKey);
    }


    public function getEncKey(): string
    {
        $encKey = $this->getParameter('encKey');
        return $encKey ?? $this->encKey;
    }

    public function setOosTdsServiceUrl(string $tdsServiceUrl)
    {
        return $this->setParameter('oosTdsServiceUrl', $tdsServiceUrl);
    }

    public function getXmlServiceUrl(): string
    {
        $serviceUrl = ($this->getTestMode()) ? $this->xmlServiceUrls['test'] : $this->xmlServiceUrls['live'];
        return $this->getParameter('xmlServiceUrl') ?? $serviceUrl;
    }

    public function setXmlServiceUrl(string $xmlServiceUrl)
    {
        return $this->setParameter('xmlServiceUrl', $xmlServiceUrl);
    }

    public function getPaymentType(): ?string
    {
        return $this->getParameter('paymentType');
    }

    public function setPaymentType(string $paymentType)
    {
        return $this->setParameter('paymentType', $paymentType);
    }

    public function getOrderID(): ?string
    {
        return $this->getParameter('orderID');
    }

    public function setOrderId(string $orderId)
    {
        return $this->setParameter('orderID', $orderId);
    }

    public function getXidByOrderId(): string
    {
        return str_pad($this->getOrderID(), 20, '0', STR_PAD_LEFT);
    }

    public function setXid(string $xid)
    {
        return $this->setParameter('xid', $xid);
    }

    public function getXid(): string
    {
        return $this->getParameter('xid');
    }

    public function getInstallment(): string
    {
        return $this->getParameter('installment');
    }

    public function setInstallment(string $installment)
    {
        return $this->setParameter('installment', $installment);
    }

    public function getWpAmount(): string
    {
        return $this->getParameter('wpAmount');
    }

    public function setWpAmount($wpAmount)
    {
        return $this->setParameter('wpAmount', $wpAmount);
    }

    public function getMerchantPacket(): string
    {
        return $this->getParameter('merchantPacket');
    }

    public function setMerchantPacket($merchantPacket)
    {
        return $this->setParameter('merchantPacket', $merchantPacket);
    }

    public function getBankPacket(): string
    {
        return $this->getParameter('bankPacket');
    }

    public function setBankPacket($bankPacket)
    {
        return $this->setParameter('bankPacket', $bankPacket);
    }

    public function getSign(): string
    {
        return $this->getParameter('sign');
    }

    public function setSign($sign)
    {
        return $this->setParameter('sign', $sign);
    }

    public function getCcPrefix(): string
    {
        return $this->getParameter('sign');
    }

    public function setCcPrefix($ccPrefix)
    {
        return $this->setParameter('ccPrefix', $ccPrefix);
    }

    public function getTranType(): string
    {
        return $this->getParameter('tranType');
    }

    public function setTranType($tranType)
    {
        return $this->setParameter('tranType', $tranType);
    }


    /**
     * Set TranDateRequired value to include time value in response data.
     *
     * @param string $tranDateRequired
     */
    public function setTranDateRequired(string $tranDateRequired): void
    {
        $this->setParameter('tranDateRequired', $tranDateRequired);
    }

    /**
     * Get TranDateRequired value.
     *
     * @return string
     */
    public function getTranDateRequired(): string
    {
        return $this->getParameter('tranDateRequired');
    }

    public function getHostLogKey(): ?string
    {
        return $this->getParameter('hostLogKey');
    }

    public function setHostLogKey($hostLogKey)
    {
        return $this->setParameter('hostLogKey', $hostLogKey);
    }

    public function getMerchantReturnUrl(): ?string
    {
        return $this->getParameter('merchantReturnUrl');
    }

    public function setMerchantReturnUrl($merchantReturnUrl)
    {
        return $this->setParameter('merchantReturnUrl', $merchantReturnUrl);
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->getParameter('websiteUrl');
    }

    public function setWebsiteUrl($websiteUrl)
    {
        return $this->setParameter('websiteUrl', $websiteUrl);
    }

    public function getTransaction(): ?string
{
    return $this->getParameter('transaction');
}

    public function setTransaction($transaction)
    {
        return $this->setParameter('transaction', $transaction);
    }

    public function getAuthCode(): ?string
    {
        return $this->getParameter('authCode');
    }

    public function setAuthCode($authCode)
    {
        return $this->setParameter('authCode', $authCode);
    }

    /**
     * @return string
     * @throws InvalidRequestException
     */
    public function getMac(): string
    {
        $encKey = $this->getEncKey();
        $terminalID = $this->getTerminalId();
        $xid = $this->getXid();
        $amount = $this->getAmount();
        $currency = $this->getCurrency();
        $merchantId = $this->getMerchantId();
        $firstHash = $this->hashString($encKey . ';' . $terminalID);
        return $this->hashString($xid . ';' . $amount . ';' . $currency . ';' . $merchantId . ';'
            . $firstHash);
    }
}