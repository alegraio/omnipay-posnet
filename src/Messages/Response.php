<?php
/**
 * PosNet  Response
 */

namespace Omnipay\PosNet\Messages;

use Exception;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use RuntimeException;

class Response extends AbstractResponse implements RedirectResponseInterface
{
    protected $statusCode;

    /**
     * Response constructor.
     * @param RequestInterface $request
     * @param $data
     * @param int $statusCode
     * @throws Exception
     */
    public function __construct(RequestInterface $request, $data, $statusCode = 200)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
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

    public function getCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param array $data
     * @return array
     */
    public function setData(array $data): array
    {
        return $this->data = $data;
    }

}
