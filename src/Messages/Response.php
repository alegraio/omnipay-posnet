<?php
/**
 * PosNet  Response
 */

namespace Omnipay\PosNet\Messages;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use RuntimeException;

class Response extends AbstractResponse implements RedirectResponseInterface
{
    protected $statusCode;

    public $serviceRequestParams;

    /**
     * Response constructor.
     * @param RequestInterface $request
     * @param $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
        $parsedXML = @simplexml_load_string($this->data);
        $content = json_decode(json_encode((array)$parsedXML), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Could not resolve xml response to array');
        }
        $this->setData($content);
    }

    /**
     * @return boolean
     */
    public function isSuccessful(): bool
    {

        return !(1 !== (int)$this->data['approved']);
    }

    /**
     * @return string|null
     */
    public function getTransactionReference(): ?string
    {
        return $this->data['hostlogkey'] ?? null;
    }

    public function getCode(): ?string
    {
        return $this->data['authCode'] ?? null;
    }

    public function getErrorCode(): ?string
    {
        return $this->data['respCode'] ?? null;
    }

    public function getMessage(): ?string
    {
        return $this->data['respText'] ?? null;
    }

    /**
     * @param array $data
     * @return array
     */
    public function setData(array $data): array
    {
        return $this->data = $data;
    }

    /**
     * @return array
     */
    public function getServiceRequestParams(): array
    {
        return $this->serviceRequestParams;
    }

    /**
     * @param array $serviceRequestParams
     */
    public function setServiceRequestParams(array $serviceRequestParams): void
    {
        $this->serviceRequestParams = $serviceRequestParams;
    }

}
