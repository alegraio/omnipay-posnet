<?php
/*
 * PosNet Complete Authorize Request
 */

namespace Omnipay\PosNet\Messages;

use Exception;

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
}
