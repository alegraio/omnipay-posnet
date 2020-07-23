<?php
/**
 * PosNet Abstract Request
 */

namespace Omnipay\PosNet\Messages;

use Omnipay\Common\Message\ResponseInterface;


abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /** @var string */
    protected $apiUrl = ' https://setmpos.ykb.com/PosnetWebService/XML';


    public function setItems($items): \Omnipay\Common\Message\AbstractRequest
    {
        return parent::setItems($items);
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

    /**
     * @return string
     */
    protected function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * @param mixed $data
     * @return ResponseInterface|Response
     */
    public function sendData($data)
    {
        $httpRequest = $this->httpClient->request($this->getHttpMethod(), $this->getApiUrl(),
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            []);

        $response = (string)$httpRequest->getBody()->getContents();
        return $this->response = $this->createResponse($response, $httpRequest->getStatusCode());
    }
}
