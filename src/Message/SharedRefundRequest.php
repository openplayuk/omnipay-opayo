<?php

namespace Omnipay\Opayo\Message;

/**
 * Opayo Shared Refund Request
 */
class SharedRefundRequest extends AbstractRequest
{
    public function getTxType()
    {
        return static::TXTYPE_REFUND;
    }

    /**
     * @return array The message body data.
     */
    public function getData()
    {
        $this->validate('amount', 'transactionReference');
        $reference = json_decode($this->getTransactionReference(), true);

        $data = $this->getBaseData();

        $data['Amount'] = $this->getAmount();
        $data['Currency'] = $this->getCurrency();

        $data['Description'] = $this->getDescription();

        // Reference to the transaction to refund.
        $data['RelatedVendorTxCode'] = $reference['VendorTxCode'];
        $data['RelatedVPSTxId'] = $reference['VPSTxId'];
        $data['RelatedSecurityKey'] = $reference['SecurityKey'];
        $data['RelatedTxAuthNo'] = $reference['TxAuthNo'];

        // The VendorTxCode for THIS refund transaction (different from original)
        $data['VendorTxCode'] = $this->getTransactionId();

        return $data;
    }
}
