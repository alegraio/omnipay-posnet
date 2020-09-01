<?php
/**
 * PosNet Refund Request
 */

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
        $data = [
            'mid' => $this->getMerchantId(),
            'tid' => $this->getTerminalId(),
            'tranDateRequired' => $this->getTranDateRequired(),
            $this->action => [
                'amount' => $this->getAmountInteger(),
                'currencyCode' => $this->getCurrency()
            ]
        ];
        if ($this->getHostLogKey() !== null) {
            $data[$this->action]['hostLogKey'] = $this->getHostLogKey();
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
     * @return RefundResponse
     * @throws Exception
     */
    protected function createResponse($data, $statusCode): RefundResponse
    {
        return new RefundResponse($this, $data, $statusCode);
    }

}
