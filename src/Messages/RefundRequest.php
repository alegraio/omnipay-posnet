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
                'amount' => $this->getAmount(),
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

    /**
     * Set TranDateRequired value to include time value in response data.
     *
     * @param string $tranDateRequired
     */
    public function setTranDateRequired(string $tranDateRequired): void
    {
        $this->setParameter('tranDateRequired', $tranDateRequired);
    }

    /**
     * Get TranDateRequired value.
     *
     * @return string
     */
    public function getTranDateRequired(): string
    {
        return $this->getParameter('tranDateRequired');
    }
}
