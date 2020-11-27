<?php
/**
 * PosNet Abstract Request
 */

namespace Omnipay\PosNet\Messages;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\PosNet\Mask;
use Omnipay\PosNet\RequestInterface;
use SimpleXMLElement;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest implements RequestInterface
{

    use HelperTrait, BaseParametersTrait;

    public const postParameterKey = 'xmldata';
    public const CURRENCIES = [
        'TRY' => 'TL',
        'EUR' => 'EU',
        'USD' => 'US'
    ];

    protected $requestParams;

    public function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
            'X-MERCHANT-ID' => $this->getMerchantId(),
            'X-TERMINAL-ID' => $this->getTerminalId(),
            'X-POSNET-ID' => $this->getPosNetId(),
            'X-CORRELATION-ID' => $this->getCorrelationId(),
        ];
    }


    /**
     * @param mixed $data
     * @return ResponseInterface|Response
     */
    public function sendData($data)
    {
        $xml = new SimpleXMLElement('<posnetRequest/>');
        $this->convertArrayToXml($data, $xml);
        $xmlStr = $xml->asXml();
        $xmlStr = preg_replace("/<\?xml.+?\?>/", '', $xmlStr);
        $xmlStr = mb_convert_encoding($xmlStr, 'UTF-8');

        $bodyArr = [
            $this::postParameterKey => $xmlStr
        ];
        $body = http_build_query($bodyArr, '', '&');
        $httpRequest = $this->httpClient->request($this->getHttpMethod(), $this->getEndpoint(),
            $this->getHeaders(),
            $body);

        $response = (string)$httpRequest->getBody()->getContents();
        return $this->createResponse($response, $httpRequest->getStatusCode());
    }

    public function getPurchaseRequestParams(): array
    {
        return [
            'mid' => $this->getMerchantId(),
            'tid' => $this->getTerminalId(),
            'tranDateRequired' => $this->getTranDateRequired(),
            $this->action => [
                'amount' => $this->getAmountInteger(),
                'ccno' => $this->getCard()->getNumber(),
                'currencyCode' => $this->getMatchingCurrency(),
                'cvc' => $this->getCard()->getCvv(),
                'expDate' => $this->getCard()->getExpiryDate('ym'),
                'orderID' => $this->getOrderID(),
                'installment' => $this->getInstallment()
            ]

        ];
    }

    /**
     * @return array
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    public function getPurchaseRequestParamsFor3D(): array
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
                'currencyCode' => $this->getMatchingCurrency(),
                'installment' => $this->getInstallment(),
                'tranType' => $this->getTranType(),
                'cardHolderName' => $this->getCard()->getName(),
                'ccno' => $this->getCard()->getNumber(),
                'expDate' => $this->getCard()->getExpiryDate('ym'),
                'cvc' => $this->getCard()->getCvv()

            ]
        ];
    }

    public function getCompletePurchaseParams(): array
    {
        return [
            'mid' => $this->getMerchantId(),
            'tid' => $this->getTerminalId(),
            $this->action => [
                'bankData' => $this->getBankPacket(),
                'wpAmount' => $this->getWpAmount(),
                'mac' => $this->getMac()
            ]
        ];
    }

    public function getMacValidationParams(): array
    {
        return [
            'mid' => $this->getMerchantId(),
            'tid' => $this->getTerminalId(),
            $this->action => [
                'bankData' => $this->getBankPacket(),
                'merchantData' => $this->getMerchantPacket(),
                'sign' => $this->getSign(),
                'mac' => $this->getMac()

            ]
        ];
    }

    public function getRefundParams(): array
    {
        $data = [
            'mid' => $this->getMerchantId(),
            'tid' => $this->getTerminalId(),
            'tranDateRequired' => $this->getTranDateRequired(),
            $this->action => [
                'amount' => $this->getAmountInteger(),
                'currencyCode' => $this->getMatchingCurrency()
            ]
        ];
        if ($this->getHostLogKey() !== null) {
            $data[$this->action]['hostLogKey'] = $this->getHostLogKey();
            return $data;
        }
        if ($this->getOrderID() !== null) {
            $data[$this->action]['orderID'] = $this->getOrderID();
        }
        return $data;
    }

    public function getVoidParams(): array
    {
        $data = [
            'mid' => $this->getMerchantId(),
            'tid' => $this->getTerminalId(),
            $this->action => [
                'transaction' => $this->getTransaction(),
                'hostLogKey' => $this->getHostLogKey()
            ]
        ];
        if ($this->getAuthCode() !== null) { // Used for VFT (Transaction with different maturity)
            $data[$this->action]['authCode'] = $this->getAuthCode();
            return $data;
        }
        if ($this->getOrderID() !== null) {
            $data[$this->action]['orderID'] = $this->getOrderID();
        }
        return $data;
    }
    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    protected function getHttpMethod(): string
    {
        return 'POST';
    }

    public function getCorrelationId(): string
    {
        return $this->getParameter('correlationId') ?: $this->getRandomString(24); // CorrelationId max 24 chars.
    }

    public function getMatchingCurrency()
    {
        $currency = $this->getParameter('currency');
        return self::CURRENCIES[$currency] ?? $currency;
    }

    protected function setRequestParams(array $data): void
    {
        array_walk_recursive($data, [$this, 'updateValue']);
        $this->requestParams = $data;
    }

    protected function updateValue(&$data, $key): void
    {
        $sensitiveData = $this->getSensitiveData();

        if (\in_array($key, $sensitiveData, true)) {
            $data = Mask::mask($data);
        }

    }

    /**
     * @return array
     */
    protected function getRequestParams(): array
    {
        return [
            'url' => $this->getEndPoint(),
            'data' => $this->requestParams,
            'method' => $this->getHttpMethod()
        ];
    }

}
