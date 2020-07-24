<?php
/*
 * PosNet Complete Authorize Request
 */

namespace Omnipay\PosNet\Messages;

use Exception;
use Omnipay\Common\Message\ResponseInterface;

class CompleteAuthorizeRequest extends AbstractRequest
{

    public $action = 'sale';

    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getData()
    {
        return [];
    }

    /**
     * @param $data
     * @param $statusCode
     * @return CompleteAuthorizeResponse
     */
    protected function createResponse($data, $statusCode): CompleteAuthorizeResponse
    {
        return new CompleteAuthorizeResponse($this, $data, $statusCode);
    }

    /**
     * @param mixed $data
     * @return ResponseInterface|Response
     */
    public function sendData($data)
    {
        $httpRequest = $this->httpClient->request($this->getHttpMethod(), $this->getXmlServiceUrl(),
            $this->getHeaders(),
            $data);

        $response = (string)$httpRequest->getBody()->getContents();
        return new CompleteAuthorizeResponse($this, $response);
    }
}
