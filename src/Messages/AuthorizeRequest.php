<?php
/**
 * PosNet Authorize Request
 */

namespace Omnipay\PosNet\Messages;

use Exception;
use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;

class AuthorizeRequest extends AbstractRequest
{

    public $action = 'oosRequestData';

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
                'amount' => $this->getAmountInteger(),
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

