<?php

namespace Omnipay\PosNet\Messages;

class MacValidationResponse extends Response
{

    public function getMdStatus(): ?int
    {
        $data = $this->getData();
        $dataResponse = $data['oosResolveMerchantDataResponse'] ?? null;
        return (isset($dataResponse['mdStatus'])) ? (int)$dataResponse['mdStatus']: null;
    }

}
