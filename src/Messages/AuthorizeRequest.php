<?php
/**
 * PosNet Authorize Request
 */

namespace Omnipay\PosNet\Messages;

use Omnipay\Common\ItemBag;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\PosNet\PosNetItemBag;

class AuthorizeRequest extends AbstractRequest
{

    public $action = 'auth';

    /**
     * Set the items in this order
     *
     * @param array|ItemBag $items An array of items in this order
     * @return AuthorizeRequest
     */
    public function setItems($items): \Omnipay\Common\Message\AbstractRequest
    {
        if ($items && !$items instanceof ItemBag) {
            $items = new PosNetItemBag($items);
        }

        return $this->setParameter('items', $items);
    }

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
        return new AuthorizeResponse($this, $response);
    }
}

