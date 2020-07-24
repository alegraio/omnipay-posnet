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
        return [];
    }

    /**
     * @param $data
     * @param $statusCode
     * @return RefundResponse
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


    /**
     * Set Return Data.
     *
     * @param array $return
     */
    public function setReturn(array $return): void
    {
        $this->setParameter('return', $return);
    }

    /**
     * Get Return Data.
     *
     * @return array
     */
    public function getReturn(): array
    {
        return $this->getParameter('return');
    }
}
