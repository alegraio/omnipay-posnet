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
use Omnipay\PosNet\Messages\PurchaseRequest;
use Omnipay\PosNet\Messages\CompleteAuthorizeRequest;
use Omnipay\PosNet\Messages\RefundRequest;
use Omnipay\PosNet\Messages\OrderTransactionRequest;


/**
 * @method RequestInterface authorize(array $options = array())
 * @method RequestInterface capture(array $options = array())
 * @method RequestInterface completePurchase(array $options = array())
 * @method RequestInterface void(array $options = array())
 * @method RequestInterface createCard(array $options = array())
 * @method RequestInterface updateCard(array $options = array())
 * @method RequestInterface deleteCard(array $options = array())
 * @method NotificationInterface acceptNotification(array $options = array())
 * @method RequestInterface fetchTransaction(array $options = [])
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
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest|RequestInterface
     */
    public function completeAuthorize(array $parameters = [])
    {
        return $this->createRequest(CompleteAuthorizeRequest::class, $parameters);
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
    public function orderTransaction(array $parameters = [])
    {
        return $this->createRequest(OrderTransactionRequest::class, $parameters);
    }
}
