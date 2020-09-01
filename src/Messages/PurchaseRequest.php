<?php
/**
 * PosNet Purchase Request
 */

namespace Omnipay\PosNet\Messages;

use Exception;
use Omnipay\Common\Exception\InvalidRequestException;

class PurchaseRequest extends AbstractRequest
{

    public $action = 'sale';

    /**
     * @inheritDoc
     * @throws InvalidRequestException
     */
    public function getData()
    {
        return [
            'mid' => $this->getMerchantId(),
            'tid' => $this->getTerminalId(),
            'tranDateRequired' => $this->getTranDateRequired(),
            $this->action => [
                'amount' => $this->getAmountInteger(),
                'ccno' => $this->getCard()->getNumber(),
                'currencyCode' => $this->getCurrency(),
                'cvc' => $this->getCard()->getCvv(),
                'expDate' => $this->getCard()->getExpiryDate('ym'),
                'orderID' => str_pad($this->getOrderID(), 24, '0', STR_PAD_LEFT),
                'installment' => $this->getInstallment()
            ]

        ];
    }

    /**
     * @param $data
     * @param $statusCode
     * @return PurchaseResponse
     * @throws Exception
     */
    protected function createResponse($data, $statusCode): PurchaseResponse
    {
        return new PurchaseResponse($this, $data, $statusCode);
    }
}
