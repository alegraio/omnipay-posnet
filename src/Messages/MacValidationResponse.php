<?php
/**
 * PosNet Mac Validation Response
 */

namespace Omnipay\PosNet\Messages;

class MacValidationResponse extends Response
{

    public function getMdStatus(): int
    {
        $data = $this->getData();
        $dataResponse = $data['oosResolveMerchantDataResponse'] ?? null;
        return $dataResponse['mdStatus'] ?? null;
    }

}
