<?php
/**
 * PosNet Authorize Request
 */

namespace Omnipay\PosNet\Messages;

use Omnipay\Common\ItemBag;
use Omnipay\PosNet\PosNetItemBag;

class AuthorizeRequest extends AbstractRequest
{
    use ConstantTrait;

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
     * @param $data
     * @param $statusCode
     * @return AuthorizeResponse
     */
    protected function createResponse($data, $statusCode): AuthorizeResponse
    {
        return new AuthorizeResponse($this, $data, $statusCode);
    }
}

