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
     * @return VoidResponse
     */
    protected function createResponse($data): VoidResponse
    {
        $response = new VoidResponse($this, $data);
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
