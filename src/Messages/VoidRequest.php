<?php
/**
 * PosNet Void Request
 */

namespace Omnipay\PosNet\Messages;

use Exception;

class VoidRequest extends AbstractRequest
{

    public $action = 'reverse';

    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getData()
    {
        $data = $this->getVoidParams();
        $this->setRequestParams($data);
        return $data;
    }

    /**
     * @param $data
     * @param $statusCode
     * @return VoidResponse
     * @throws Exception
     */
    protected function createResponse($data, $statusCode): VoidResponse
    {
        $response = new VoidResponse($this, $data, $statusCode);
        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);

        return $response;
    }


    /**
     * @return string
     */
    public function getProcessName(): string
    {
        return 'void';
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
