<?php
/**
 * PosNet Purchase Request
 */

namespace Omnipay\PosNet\Messages;

use Exception;
use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;

class PurchaseRequest extends AbstractRequest
{

    private const PAYMENT_TYPE_3D = "3d";
    public $actions = [
        'direct' => 'sale',
        '3d' => 'oosRequestData'
    ];

    public $action = 'sale';
    /**
     * @inheritDoc
     * @throws InvalidRequestException
     * @throws InvalidCreditCardException
     */
    public function getData()
    {
        if ($this->getPaymentMethod() === self::PAYMENT_TYPE_3D) {
            $this->action = $this->actions[self::PAYMENT_TYPE_3D];
            $data = $this->getPurchaseRequestParamsFor3D();
        } else {
            $data = $this->getPurchaseRequestParams();
        }
        $this->setRequestParams($data);

        return $data;
    }

    /**
     * @param $data
     * @param $statusCode
     * @return PurchaseResponse|Purchase3DResponse
     * @throws Exception
     */
    protected function createResponse($data, $statusCode): PurchaseResponse
    {
        if ($this->getPaymentMethod() === self::PAYMENT_TYPE_3D) {
            $response = new Purchase3DResponse($this, $data, $statusCode);
        } else {
            $response = new PurchaseResponse($this, $data, $statusCode);
        }

        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);

        return $response;
    }

    public function getOosTdsServiceUrl(): string
    {
        $tdsServiceUrl = $this->getParameter('oosTdsServiceUrl');
        $tdsStaticServiceUrl = ($this->getTestMode()) ? $this->xmlServiceUrls['test3d'] : $this->xmlServiceUrls['3d'];
        return $tdsServiceUrl ?: $tdsStaticServiceUrl;
    }


    /**
     * @return string
     */
    public function getProcessName(): string
    {
        if ($this->getPaymentMethod() === self::PAYMENT_TYPE_3D) {
            return 'purchase3D';
        }
        return 'purchase';
    }

    /**
     * @return string
     */
    public function getProcessType(): string
    {
        if ($this->getPaymentMethod() === self::PAYMENT_TYPE_3D) {
            return $this->actions[self::PAYMENT_TYPE_3D];
        }
        return $this->action;
    }

    /**
     * @return array
     */
    public function getSensitiveData(): array
    {
        if ($this->getPaymentMethod() === self::PAYMENT_TYPE_3D) {
            return ['mid', 'tid', 'posnetid', 'XID'];
        }

        return ['mid', 'tid', 'ccno', 'cvc', 'expDate'];
    }
}
