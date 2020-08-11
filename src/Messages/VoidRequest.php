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
        $data = [
            'mid' => $this->getMerchantId(),
            'tid' => $this->getTerminalId(),
            $this->action => [
                'transaction' => $this->getTransaction(),
                'hostLogKey' => $this->getHostLogKey()
            ]
        ];
        if ($this->getAuthCode() !== null) { // Used for VFT (Transaction with different maturity)
            $data[$this->action]['authCode'] = $this->getAuthCode();
            return $data;
        }
        if ($this->getOrderID() !== null) {
            $data[$this->action]['orderID'] = $this->getOrderID();
        }
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
        return new VoidResponse($this, $data, $statusCode);
    }
}
