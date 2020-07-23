<?php
/**
 * PosNet Refund Request
 */

namespace Omnipay\PosNet\Messages;

use Exception;

class RefundRequest extends AbstractRequest
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
     * @return RefundResponse
     */
    protected function createResponse($data, $statusCode): RefundResponse
    {
        return new RefundResponse($this, $data, $statusCode);
    }
}
