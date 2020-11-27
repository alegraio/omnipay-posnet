<?php
/*
 * PosNet Complete Purchase Request
 */

namespace Omnipay\PosNet\Messages;

use Exception;

class CompletePurchaseRequest extends AbstractRequest
{

    use HelperTrait;

    public $action = 'oosTranData';

    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getData()
    {
        $data = $this->getCompletePurchaseParams();
        $this->setRequestParams($data);
        return $data;

    }

    /**
     * @param $data
     * @param $statusCode
     * @return CompletePurchaseResponse
     * @throws Exception
     */
    protected function createResponse($data, $statusCode): CompletePurchaseResponse
    {
        $response = new CompletePurchaseResponse($this, $data, $statusCode);
        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);

        return $response;
    }

    /**
     * @return string
     */
    public function getProcessName(): string
    {
        return 'completePurchase';
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
        return ['mid', 'tid', 'mac'];
    }
}
