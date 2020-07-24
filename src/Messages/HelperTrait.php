<?php

namespace Omnipay\PosNet\Messages;

trait HelperTrait
{
    public function getRandomString(int $length)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($permitted_chars), 0, $length);
    }
}