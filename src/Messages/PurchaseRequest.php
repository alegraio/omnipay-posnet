<?php
/**
 * PosNet Purchase Request
 */

namespace Omnipay\PosNet\Messages;

use Omnipay\Common\Message\ResponseInterface;

class PurchaseRequest extends AbstractRequest
{

    public $action = 'sale';

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return [];
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
        return new PurchaseResponse($this, $response);
    }
}
