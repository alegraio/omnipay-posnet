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
        return [
            'mid' => $this->getMerchantId(),
            'tid' => $this->getTerminalId(),
            $this->action => [
                'bankData' => $this->getBankPacket(),
                'wpAmount' => $this->getWpAmount(),
                'mac' => $this->getMac()
            ]
        ];
    }

    /**
     * @param $data
     * @param $statusCode
     * @return CompletePurchaseResponse
     * @throws Exception
     */
    protected function createResponse($data, $statusCode): CompletePurchaseResponse
    {
        return new CompletePurchaseResponse($this, $data, $statusCode);
    }
}
