<?php
/**
 * PosNet Mac Validation Request
 */

namespace Omnipay\PosNet\Messages;

use Exception;

class MacValidationRequest extends AbstractRequest
{

    use HelperTrait;

    public $action = 'oosResolveMerchantData';

    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getData()
    {
        $data = $this->getMacValidationParams();
        $this->setRequestParams($data);
        return $data;
    }

    /**
     * @param $data
     * @return MacValidationResponse
     */
    protected function createResponse($data): MacValidationResponse
    {
        $response = new MacValidationResponse($this, $data);
        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);

        return $response;
    }


    /**
     * @return string
     */
    public function getProcessName(): string
    {
        return 'macValidation';
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
