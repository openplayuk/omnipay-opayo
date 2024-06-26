<?php

namespace Omnipay\Opayo\Message;

/**
 * Opayo REST Server Purchase Request
 */
class ServerRestPurchaseRequest extends AbstractRestRequest
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
        return ucfirst(strtolower(static::TXTYPE_PAYMENT));
    }

    /**
     * Add the optional token details to the base data.
     *
     * @return array
     */
    public function getData()
    {
        $data = $this->getBasePurchaseData();

        if ($this->getCardIdentifier() && $this->getMerchantSessionKey()) {
            $data = $this->getPaymentMethodData($data);
        }

        return $data;
    }

    /**
     * The required fields concerning the purchase
     *
     * @return array
     */
    protected function getBasePurchaseData()
    {
        $card = $this->getCard();

        $data = $this->getBaseData();

        $data['transactionType'] = $this->getTxType();
        $data['vendorTxCode'] = $this->getTransactionId();
        $data['description'] = $this->getDescription();
        $data['amount'] = $this->getAmountInteger();
        $data['currency'] = $this->getCurrency();
        $data['NotificationURL'] = $this->getNotifyUrl() ?: $this->getReturnUrl();
        $data['MD'] = $this->getMd();
        $data['strongCustomerAuthentication'] = $this->getStrongCustomerAuthentication();
        $data['credentialType'] = $this->getCredentialType();

        $data = $this->getBillingAddressData($data);
        $data = $this->getShippingDetailsData($data);

        if ($card->getEmail()) {
            $data['customerEmail'] = $card->getEmail();
        }

        $data['applyAvsCvcCheck'] = $this->getApplyAvsCvcCheck() ?? static::REST_APPLY_AVS_CVC_CHECK_DEFAULT;
        $data['apply3DSecure'] = $this->getApply3DSecure() ?? static::REST_APPLY_3DSECURE_DEFAULT;

        return $data;
    }

    /**
     * @param array $data
     * @return ServerRestPurchaseResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new ServerRestPurchaseResponse($this, $data);
    }

    /**
     * @param array $data
     * @return array $data.
     */
    public function getPaymentMethodData($data = [])
    {
        $data['paymentMethod']['card']['merchantSessionKey'] = $this->getMerchantSessionKey();
        $data['paymentMethod']['card']['cardIdentifier'] = $this->getCardIdentifier();
        if (! is_null($this->getTokenReusable())) {
            $data['paymentMethod']['card']['reusable'] = $this->getTokenReusable();
        }
        if (! is_null($this->getTokenSave())) {
            $data['paymentMethod']['card']['save'] = $this->getTokenSave();
        }
        return $data;
    }

    /**
     * Set the strongCustomerAuthentication field(s).
     *
     * @param json $strongCustomerAuthentication The strongCustomerAuthentication for Opayo.
     * @return $this
     */
    public function setStrongCustomerAuthentication($strongCustomerAuthentication)
    {
        return $this->setParameter('strongCustomerAuthentication', $strongCustomerAuthentication);
    }

    /**
     * @return string The strongCustomerAuthentication data as set.
     */
    public function getStrongCustomerAuthentication()
    {
        return $this->getParameter('strongCustomerAuthentication');
    }
}
