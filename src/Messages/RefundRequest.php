<?php

namespace Omnipay\PosNet\Messages;

use Exception;

class RefundRequest extends AbstractRequest
{

    public $action = 'return';

    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getData()
    {
        $data = $this->getRefundParams();
        $this->setRequestParams($data);
        return $data;
    }

    /**
     * @param $data
     * @return RefundResponse
     * @throws \JsonException
     */
    protected function createResponse($data): RefundResponse
    {
        $response = new RefundResponse($this, $data);
        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);

        return $response;
    }


    /**
     * @return string
     */
    public function getProcessName(): string
    {
        return 'refund';
    }

    /**
     * @return string
     */
    public function getProcessType(): string
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getSensitiveData(): array
    {
        return ['mid', 'tid'];
    }

}
