<?php
/**
 * PosNet Abstract Request
 */

namespace Omnipay\PosNet\Messages;

use Omnipay\Common\Message\ResponseInterface;
use SimpleXMLElement;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{

    use HelperTrait, BaseParametersTrait;

    public const postParameterKey = 'xmldata';

    public function setItems($items): \Omnipay\Common\Message\AbstractRequest
    {
        return parent::setItems($items);
    }

    public function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
            'X-MERCHANT-ID' => $this->getMerchantId(),
            'X-TERMINAL-ID' => $this->getTerminalId(),
            'X-POSNET-ID' => $this->getPosNetId(),
            'X-CORRELATION-ID' => $this->getCorrelationId(),
        ];
    }


    /**
     * @param mixed $data
     * @return ResponseInterface|Response
     */
    public function sendData($data)
    {
        $xml = new SimpleXMLElement('<posnetRequest/>');
        $this->convertArrayToXml($data, $xml);
        $xmlStr = $xml->asXml();
        $xmlStr = preg_replace( "/<\?xml.+?\?>/", '', $xmlStr);
        $xmlStr = mb_convert_encoding($xmlStr, 'UTF-8');

        $bodyArr = [
            $this::postParameterKey => $xmlStr
        ];
        $body = http_build_query($bodyArr, '', '&');
        $httpRequest = $this->httpClient->request($this->getHttpMethod(), $this->getXmlServiceUrl(),
            $this->getHeaders(),
            $body);

        $response = (string)$httpRequest->getBody()->getContents();
        return $this->createResponse($response, $httpRequest->getStatusCode());
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
