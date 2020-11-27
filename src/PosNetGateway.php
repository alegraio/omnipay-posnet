<?php
/**
 * PosNet Class using API
 */

namespace Omnipay\PosNet;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\PosNet\Messages\BaseParametersTrait;
use Omnipay\PosNet\Messages\MacValidationRequest;
use Omnipay\PosNet\Messages\PurchaseRequest;
use Omnipay\PosNet\Messages\CompletePurchaseRequest;
use Omnipay\PosNet\Messages\RefundRequest;
use Omnipay\PosNet\Messages\VoidRequest;


/**
 * @method RequestInterface capture(array $options = array())
 * @method RequestInterface createCard(array $options = array())
 * @method RequestInterface updateCard(array $options = array())
 * @method RequestInterface deleteCard(array $options = array())
 * @method NotificationInterface acceptNotification(array $options = array())
 * @method RequestInterface fetchTransaction(array $options = [])
 * @method RequestInterface completeAuthorize(array $options = array())
 * @method RequestInterface authorize(array $options = array())
 */
class PosNetGateway extends AbstractGateway
{

    use BaseParametersTrait;

    public function getDefaultParameters(): array
    {
        return [
          'merchantId' => '',
          'terminalId' => '',
          'posNetId' => '',
          'encKey' => ''
        ];
    }


    public function setMerchantId(string $merchantId): PosNetGateway
    {
        return $this->setParameter('merchantId', $merchantId);
    }

    public function getMerchantId(): ?string
    {
        return $this->getParameter('merchantId');
    }

    public function setTerminalId(string $terminalId): PosNetGateway
    {
        return $this->setParameter('terminalId', $terminalId);
    }

    public function getTerminalId(): ?string
    {
        return $this->getParameter('terminalId');
    }

    public function setPosNetId(string $posNetId): PosNetGateway
    {
        return $this->setParameter('posNetId', $posNetId);
    }

    public function getPosNetId(): ?string
    {
        return $this->getParameter('posNetId');
    }


    public function setEncKey(string $encKey): PosNetGateway
    {
        return $this->setParameter('encKey', $encKey);
    }


    public function getEncKey(): ?string
    {
        $encKey = $this->getParameter('encKey');
        return $encKey ?? $this->encKey;
    }

    public function setOosTdsServiceUrl(string $tdsServiceUrl): PosNetGateway
    {
        return $this->setParameter('oosTdsServiceUrl', $tdsServiceUrl);
    }

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     * @return string
     */
    public function getName(): string
    {
        return 'PosNet';
    }

    /**
     * @param array $parameters
     * @return AbstractRequest|PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest|CompletePurchaseRequest|null
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);

    }

    /**
     * @param array $parameters
     * @return AbstractRequest|RefundRequest
     */
    public function validateMac(array $parameters = [])
    {
        return $this->createRequest(MacValidationRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest|RefundRequest
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest|VoidRequest
     */
    public function void(array $parameters = [])
    {
        return $this->createRequest(VoidRequest::class, $parameters);
    }
}
