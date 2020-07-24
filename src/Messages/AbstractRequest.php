<?php
/**
 * PosNet Abstract Request
 */

namespace Omnipay\PosNet\Messages;

use Omnipay\Common\Message\ResponseInterface;


abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{

    use HelperTrait;

    public const xmlServiceUrl = 'https://setmpos.ykb.com/PosnetWebService/XML';
    public const postParameterKey = 'xmldata';

    public function setItems($items): \Omnipay\Common\Message\AbstractRequest
    {
        return parent::setItems($items);
    }


    public function getXmlServiceUrl(): string
    {
        return $this->getParameter('xmlServiceUrl') ?? self::xmlServiceUrl;
    }

    public function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'X-MERCHANT-ID' => $this->getParameter('merchantId'),
            'X-TERMINAL-ID' => $this->getParameter('terminalId'),
            'X-POSNET-ID' => $this->getParameter('posNetId'),
            'X-CORRELATION-ID' => $this->getCorrelationId(),
        ];
    }

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    protected function getHttpMethod(): string
    {
        return 'POST';
    }

    public function getCorrelationId(): string
    {
        return $this->getParameter('correlationId') ?: $this->getRandomString(24); // CorrelationId max 24 chars.
    }
}
