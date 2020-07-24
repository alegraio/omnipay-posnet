<?php
/**
 * PosNet Purchase Request
 */

namespace Omnipay\PosNet\Messages;

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
     * @param $data
     * @param $statusCode
     * @return PurchaseResponse
     */
    protected function createResponse($data, $statusCode): PurchaseResponse
    {
        return new PurchaseResponse($this, $data, $statusCode);
    }
}
