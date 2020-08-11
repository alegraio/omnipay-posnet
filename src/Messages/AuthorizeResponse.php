<?php
/**
 * PosNet Authorize Response
 */

namespace Omnipay\PosNet\Messages;


use DOMDocument;

class AuthorizeResponse extends Response
{

    use BaseParametersTrait;

    /**
     * @var array
     */
    private $parameters;


    /**
     * @return bool
     */
    public function isRedirect(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->request->getOosTdsServiceUrl();
    }

    /**
     * @return array|mixed
     */
    public function getData()
    {
        $responseData = $this->data;
        return array_merge($responseData, $this->getThreeDSFormData());
    }

    public function getRedirectData(): ?array
    {
        return $this->getThreeDSFormData();
    }

    /**
     * @return array|null
     */
    public function getThreeDSFormData(): ?array
    {
        $responseData = $this->data;
        if (!$this->isSuccessful()) {
            return [];
        }
        $posnetData = $responseData['oosRequestDataResponse']['data1'];
        $posnetData2 = $responseData['oosRequestDataResponse']['data2'];
        $digest = $responseData['oosRequestDataResponse']['sign'];
        $requestParameters = $this->request->getParameters();
        $this->parameters = $this->parameters ?? $requestParameters;
        $formData = [
            'mid' => $this->parameters['merchantId'],
            'posnetID' => $this->parameters['posNetId'],
            'posnetData' => $posnetData,
            'posnetData2' => $posnetData2,
            'digest' => $digest,
            'vftCode' => $this->parameters['vftCode'] ?? '',
            'merchantReturnURL' => $this->parameters['merchantReturnUrl'],
            'lang' => 'tr',
            'url' => $this->parameters['websiteUrl'],
            'openANewWindow' => '0',
            'oosTdsServiceUrl' => $this->request->getOosTdsServiceUrl()
        ];
        if (isset($this->parameters['useJokerVadaa'])) {
            $formData['useJokerVadaa'] = $this->parameters['useJokerVadaa'];
        }

        return $formData;
    }

    /**
     * @return string|null
     */
    public function getHtml(): ?string
    {
        $dom = new DOMDocument('1.0');
        $formData = $this->getThreeDSFormData();

        $html = $dom->createElement('html');
        $html->setAttribute('lang', 'en');
        $html->setAttribute('xmlns', 'http://www.w3.org/1999/xhtml');

        $head = $dom->createElement('head');

        $meta = $dom->createElement('meta');
        $meta->setAttribute('charset', 'utf-8');

        $title = $dom->createElement('title');
        $posnetJs = $dom->createElement('script', $this->getPosnetJs());
        $posnetJs->setAttribute('type', 'text/javascript');

        $head->appendChild($meta);
        $head->appendChild($title);
        $head->appendChild($posnetJs);
        $html->appendChild($head);

        $body = $dom->createElement('body');
        $form = $dom->createElement('form');

        $form->setAttribute('name', 'posnetForm');
        $form->setAttribute('method', 'post');
        $form->setAttribute('action', $formData['oosTdsServiceUrl']);
        $form->setAttribute('target', 'YKBWindow');

        $mid = $dom->createElement('input');
        $posnetID = $dom->createElement('input');
        $posnetData = $dom->createElement('input');
        $posnetData2 = $dom->createElement('input');
        $digest = $dom->createElement('input');
        $vftCode = $dom->createElement('input');
        $useJokerVadaa = $dom->createElement('input');
        $merchantReturnURL = $dom->createElement('input');
        $lang = $dom->createElement('input');
        $url = $dom->createElement('input');
        $openANewWindow = $dom->createElement('input');

        $mid->setAttribute('name', 'mid');
        $mid->setAttribute('type', 'hidden');
        $mid->setAttribute('id', 'mid');
        $mid->setAttribute('value', $formData['mid']);

        $posnetID->setAttribute('name', 'posnetID');
        $posnetID->setAttribute('type', 'hidden');
        $posnetID->setAttribute('id', 'PosnetID');
        $posnetID->setAttribute('value', $formData['posnetID']);

        $posnetData->setAttribute('name', 'posnetData');
        $posnetData->setAttribute('type', 'hidden');
        $posnetData->setAttribute('id', 'posnetData');
        $posnetData->setAttribute('value', $formData['posnetData']);

        $posnetData2->setAttribute('name', 'posnetData2');
        $posnetData2->setAttribute('type', 'hidden');
        $posnetData2->setAttribute('id', 'posnetData2');
        $posnetData2->setAttribute('value', $formData['posnetData2']);

        $digest->setAttribute('name', 'digest');
        $digest->setAttribute('type', 'hidden');
        $digest->setAttribute('id', 'sign');
        $digest->setAttribute('value', $formData['digest']);

        $vftCode->setAttribute('name', 'vftCode');
        $vftCode->setAttribute('type', 'hidden');
        $vftCode->setAttribute('id', 'vftCode');
        $vftCode->setAttribute('value', $formData['vftCode']);

        if (isset($formData['useJokerVadaa'])) {
            $useJokerVadaa->setAttribute('name', 'useJokerVadaa');
            $useJokerVadaa->setAttribute('type', 'hidden');
            $useJokerVadaa->setAttribute('id', 'useJokerVadaa');
            $useJokerVadaa->setAttribute('value', 1);
        }

        $merchantReturnURL->setAttribute('name', 'merchantReturnURL');
        $merchantReturnURL->setAttribute('type', 'hidden');
        $merchantReturnURL->setAttribute('id', 'merchantReturnURL');
        $merchantReturnURL->setAttribute('value', $formData['merchantReturnURL']);

        $lang->setAttribute('name', 'lang');
        $lang->setAttribute('type', 'hidden');
        $lang->setAttribute('id', 'lang');
        $lang->setAttribute('value', $formData['lang']);

        $url->setAttribute('name', 'url');
        $url->setAttribute('type', 'hidden');
        $url->setAttribute('id', 'url');
        $url->setAttribute('value', $formData['url']);

        $openANewWindow->setAttribute('name', 'openANewWindow');
        $openANewWindow->setAttribute('type', 'hidden');
        $openANewWindow->setAttribute('id', 'openANewWindow');
        $openANewWindow->setAttribute('value', $formData['openANewWindow']);

        $form->appendChild($mid);
        $form->appendChild($posnetID);
        $form->appendChild($posnetData);
        $form->appendChild($posnetData2);
        $form->appendChild($digest);
        $form->appendChild($vftCode);
        $form->appendChild($merchantReturnURL);
        $form->appendChild($lang);
        $form->appendChild($url);
        $form->appendChild($openANewWindow);

        $body->appendChild($form);
        $html->appendChild($body);

        $dom->appendChild($html);
        return $dom->saveHTML();
    }

    /**
     * @return string
     */
    public function getPosnetJs(): string
    {
        return <<<EOF
             // JavaScript Document
            function submitForm(Form, OpenNewWindowFlag, WindowName) {
            
                var isURLExist = false;
                var isNewWindowExist = false;
            
                if (document.all || document.getElementById) {
                    for (i = 0; i < Form.length; i++) {
                        var tempobj = Form.elements[i];
            
                        if (tempobj.name.toLowerCase() == "url") {
                            Form.url.value = location.href;
                            isURLExist = true;
                        }
            
                        if (OpenNewWindowFlag && tempobj.name == "openANewWindow") {
                            tempobj.value = "1";
                            isNewWindowExist = true;
                        } else if (tempobj.name == "openANewWindow") {
                            tempobj.value = "0";
                            isNewWindowExist = true;
                        }
                    }
                }
            
                if (!isURLExist) {
                    el = document.createElement("input");
                    el.name = "url";
                    el.type = "hidden";
                    el.value = location.href;
                    el = Form.appendChild(el);
                }
            
                if (!isNewWindowExist) {
                    el2 = document.createElement("input");
                    el2.name = "openANewWindow";
                    el2.type = "hidden";
                    if (OpenNewWindowFlag)
                        el2.value = "1";
                    else
                        el2.value = "0";
                    el2 = Form.appendChild(el2);
                }
            
                if (OpenNewWindowFlag) {
                    window.name = "merchantWindow";
                    openWindow(WindowName, 650, 600);
                } else
                    window.name = "YKBWindow";
            }
            
            function openWindow(WindowName, width, height) {
                x = (640 - width) / 2, y = (480 - height) / 2;
            
                if (screen) {
                    y = (screen.availHeight - height - 70) / 2;
                    x = (screen.availWidth - width) / 2;
                }
            
                window.open('', WindowName, 'width=' + width + ',height=' + height + ',screenX=' + x + ',screenY=' + y + ',top=' + y + ',left=' + x + ',status=yes,location=yes,resizable=no,scrollbars=yes');
            }
            
            function submitFormEx(Form, OpenNewWindowFlag, WindowName) {
                submitForm(Form, OpenNewWindowFlag, WindowName)
                Form.submit();
            }
            
            submitFormEx(posnetForm, 0, 'YKBWindow');
EOF;
    }
}
