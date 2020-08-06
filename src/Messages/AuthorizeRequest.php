<?php
/**
 * PosNet Authorize Request
 */

namespace Omnipay\PosNet\Messages;

use Exception;
use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\ItemBag;
use Omnipay\PosNet\PosNetItemBag;

class AuthorizeRequest extends AbstractRequest
{

    public $action = 'oosRequestData';

    /**
     * Set the items in this order
     *
     * @param array|ItemBag $items An array of items in this order
     * @return AuthorizeRequest
     */
    public function setItems($items): \Omnipay\Common\Message\AbstractRequest
    {
        if ($items && !$items instanceof ItemBag) {
            $items = new PosNetItemBag($items);
        }

        return $this->setParameter('items', $items);
    }

    /**
     * @return mixed
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('card');
        $this->getCard()->validate();

        return [
            'mid' => $this->getMerchantId(),
            'tid' => $this->getTerminalId(),
            $this->action => [
                'posnetid' => $this->getPosNetId(),
                'XID' => $this->getXidByOrderId(),
                'amount' => $this->getAmount(),
                'currencyCode' => $this->getCurrency(),
                'installment' => $this->getInstallment(),
                'tranType' => $this->getTranType(),
                'cardHolderName' => $this->getCard()->getName(),
                'ccno' => $this->getCard()->getNumber(),
                'expDate' => $this->getCard()->getExpiryDate('ym'),
                'cvc' => $this->getCard()->getCvv()

            ]
        ];

    }


    public function getOosTdsServiceUrl(): string
    {
        $tdsServiceUrl = $this->getParameter('oosTdsServiceUrl');
        $tdsStaticServiceUrl = ($this->getTestMode()) ? $this->xmlServiceUrls['test3d'] : $this->xmlServiceUrls['3d'];
        return $tdsServiceUrl ?: $tdsStaticServiceUrl;
    }

    /**
     * @param $data
     * @param $statusCode
     * @return AuthorizeResponse
     * @throws Exception
     */
    protected function createResponse($data, $statusCode): AuthorizeResponse
    {
        return new AuthorizeResponse($this, $data, $statusCode);
    }
}

