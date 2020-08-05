<?php


namespace Omnipay\PosNet\Messages;


trait BaseParametersTrait
{
    public $xmlServiceUrls = [
        'test' => 'https://setmpos.ykb.com/PosnetWebService/XML',
        'live' => 'https://www.posnet.ykb.com/PosnetWebService/XML',
        'test3d' => 'https://setmpos.ykb.com/3DSWebService/YKBPaymentService',
        '3d' => 'https://www.posnet.ykb.com/3DSWebService/YKBPaymentService'
    ];

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

    public function getXmlServiceUrl(): string
    {
        $serviceUrl = ($this->getTestMode()) ? $this->xmlServiceUrls['test'] : $this->xmlServiceUrls['live'];
        return $this->getParameter('xmlServiceUrl') ?? $serviceUrl;
    }

    public function setXmlServiceUrl(string $xmlServiceUrl)
    {
        return $this->setParameter('xmlServiceUrl', $xmlServiceUrl);
    }

    public function getThreeDServiceUrl(): string
    {
        return ($this->parameters['testMode']) ? $this->xmlServiceUrls['test3d'] : $this->xmlServiceUrls['3d'];
    }

    public function getOrderID(): ?string
    {
        return $this->getParameter('orderID');
    }

    public function setOrderId(string $orderId)
    {
        return $this->setParameter('orderID', $orderId);
    }

    public function getXid(): string
    {
        return str_pad($this->getOrderID(), 20, '0', STR_PAD_LEFT);
    }

    public function getInstallment(): string
    {
        return $this->getParameter('installment');
    }

    public function setInstallment(string $installment)
    {
        return $this->setParameter('installment', $installment);
    }

    public function getAmount(): string
    {
        return $this->getParameter('amount');
    }

    public function setAmount($amount)
    {
        return $this->setParameter('amount', $amount);
    }

    public function getTranType(): string
    {
        return $this->getParameter('tranType');
    }

    public function setTranType($tranType)
    {
        return $this->setParameter('tranType', $tranType);
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
}