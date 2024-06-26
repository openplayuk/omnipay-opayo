<?php

namespace Omnipay\Opayo\Message;

use Omnipay\Opayo\Message\ServerRestRefundResponse;

/**
 * Opayo REST Server Refund Request
 */
class ServerRestRefundRequest extends AbstractRestRequest
{
    public function getService()
    {
        return static::SERVICE_REST_TRANSACTIONS;
    }

    /**
     * @return string the transaction type
     */
    public function getTxType()
    {
        return ucfirst(strtolower(static::TXTYPE_REFUND));
    }

    /**
     * Add the optional token details to the base data.
     *
     * @return array
     */
    public function getData()
    {
        $data = $this->getBaseData();

        $data['transactionType'] = $this->getTxType();
        $data['vendorTxCode'] = $this->getTransactionId();
        $data['description'] = $this->getDescription();
        $data['amount'] = $this->getAmountInteger();
        // $data['currency'] = $this->getCurrency();
        $data['referenceTransactionId'] = $this->getReferenceTransactionId();

        return $data;
    }

    /**
     * @param array $data
     * @return ServerRestPurchaseKeyResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new ServerRestRefundResponse($this, $data);
    }

    public function getReferenceTransactionId()
    {
        return $this->getParameter('referenceTransactionId');
    }

    public function setReferenceTransactionId($value)
    {
        return $this->setParameter('referenceTransactionId', $value);
    }
}
