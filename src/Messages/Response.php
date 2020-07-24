<?php
/**
 * PosNet  Response
 */

namespace Omnipay\PosNet\Messages;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

class Response extends AbstractResponse implements RedirectResponseInterface
{
    protected $statusCode;

    public function __construct(RequestInterface $request, $data, $statusCode = 200)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
        $parsedXML = @simplexml_load_string($this->getData());
        $content = json_decode(json_encode((array)$parsedXML, JSON_THROW_ON_ERROR, 512), true, 512, JSON_THROW_ON_ERROR);
        $this->setData($content);
    }

    /**
     * @return boolean
     */
    public function isSuccessful(): bool
    {

        return !(1 !== $this->data['approved']);
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
