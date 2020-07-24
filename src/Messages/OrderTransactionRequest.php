<?php
/**
 * PosNet Order Transaction Request
 */

namespace Omnipay\PosNet\Messages;

use Exception;
use Omnipay\Common\Message\ResponseInterface;

class OrderTransactionRequest extends AbstractRequest
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
     * @return OrderTransactionResponse
     */
    protected function createResponse($data, $statusCode): OrderTransactionResponse
    {
        return new OrderTransactionResponse($this, $data, $statusCode);
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
        return new OrderTransactionResponse($this, $response);
    }
}
