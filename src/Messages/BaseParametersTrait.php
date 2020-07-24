<?php


namespace Omnipay\PosNet\Messages;


trait BaseParametersTrait
{
    public $xmlServiceUrl = 'https://setmpos.ykb.com/PosnetWebService/XML';

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
        return $this->getParameter('xmlServiceUrl') ?? $this->xmlServiceUrl;
    }

    public function setXmlServiceUrl(string $xmlServiceUrl)
    {
        return $this->setParameter('xmlServiceUrl', $xmlServiceUrl);
    }
}