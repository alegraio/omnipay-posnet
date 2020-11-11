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
        return [
            'mid' => $this->getMerchantId(),
            'tid' => $this->getTerminalId(),
            $this->action => [
                'bankData' => $this->getBankPacket(),
                'merchantData' => $this->getMerchantPacket(),
                'sign' => $this->getSign(),
                'mac' => $this->getMac()

            ]
        ];
    }

    /**
     * @param $data
     * @param $statusCode
     * @return MacValidationResponse
     * @throws Exception
     */
    protected function createResponse($data, $statusCode): MacValidationResponse
    {
        $response = new MacValidationResponse($this, $data, $statusCode);
        $response->setServiceRequestParams($data);

        return $response;
    }
}
