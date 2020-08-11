<?php
/**
 * PosNet Class using API
 */

namespace Omnipay\PosNet;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\PosNet\Messages\AuthorizeRequest;
use Omnipay\PosNet\Messages\BaseParametersTrait;
use Omnipay\PosNet\Messages\MacValidationException;
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
 */
class PosNetGateway extends AbstractGateway
{

    use BaseParametersTrait;

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
     * @return AbstractRequest|RequestInterface
     */
    public function authorize(array $parameters = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest|RequestInterface
     */
    public function purchase(array $parameters = [])
    {
        $i = $parameters['paymentType'] ?? null;
        if ($i === '3d') {
            return $this->createRequest(AuthorizeRequest::class, $parameters);
        }

        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest|RequestInterface
     * @throws MacValidationException
     */
    public function completePurchase(array $parameters = [])
    {
        $macValidationResponse = $this->createRequest(MacValidationRequest::class, $parameters)->send();
        if ($macValidationResponse->isSuccessful() && $macValidationResponse->getMdStatus() === 1) {
            return $this->createRequest(CompletePurchaseRequest::class, $parameters);
        }

        throw new MacValidationException(json_encode($macValidationResponse->getData(), JSON_THROW_ON_ERROR, 512));

    }

    /**
     * @param array $parameters
     * @return AbstractRequest|RequestInterface
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest|RequestInterface
     */
    public function void(array $parameters = [])
    {
        return $this->createRequest(VoidRequest::class, $parameters);
    }
}
