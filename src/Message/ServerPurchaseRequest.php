<?php

namespace Omnipay\Opayo\Message;

/**
 * Opayo Server Purchase Request
 */
class ServerPurchaseRequest extends ServerAuthorizeRequest
{
    public function getData()
    {
        $data = parent::getData();

        return $data;
    }

    /**
     * @return string the transaction type
     */
    public function getTxType()
    {
        return static::TXTYPE_PAYMENT;
    }
}
