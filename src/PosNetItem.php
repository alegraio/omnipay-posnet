<?php

namespace Omnipay\PosNet;

use Omnipay\Common\Item;

class PosNetItem extends Item implements PosNetItemInterface
{
    /**
     * Set the sku
     * @param $value
     * @return PosNetItem
     */
    public function setSku($value): PosNetItem
    {
        return $this->setParameter('sku', $value);
    }

    public function getSku()
    {
        return $this->getParameter('sku');
    }
}