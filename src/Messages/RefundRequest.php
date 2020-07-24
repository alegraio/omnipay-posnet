<?php
/**
 * PosNet Refund Request
 */

namespace Omnipay\PosNet\Messages;

use Exception;
use Omnipay\Common\Message\ResponseInterface;

class RefundRequest extends AbstractRequest
{

    public $action = 'return';

    /**
     * @return array|mixed
     * @throws Exception
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
        return new RefundResponse($this, $response);
    }
}
