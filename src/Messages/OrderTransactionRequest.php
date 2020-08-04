<?php
/**
 * PosNet Order Transaction Request
 */

namespace Omnipay\PosNet\Messages;

use Exception;

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
     * @throws Exception
     */
    protected function createResponse($data, $statusCode): OrderTransactionResponse
    {
        return new OrderTransactionResponse($this, $data, $statusCode);
    }
}
