<?php
/**
 * PosNet Order Transaction Request
 */

namespace Omnipay\PosNet\Messages;

use Exception;

class OrderTransactionRequest extends AbstractRequest
{
    use ConstantTrait;

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
}
