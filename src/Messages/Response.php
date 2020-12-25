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
     * @throws \JsonException
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
        $parsedXML = @simplexml_load_string($this->data);
        $content = json_decode(json_encode((array)$parsedXML, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
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
        $approved = isset($this->data['approved']) ? (int)$this->data['approved'] : 0;
        return !(1 !== $approved);
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
