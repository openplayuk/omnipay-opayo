<?php

namespace Omnipay\Opayo;

// CHECKME: do we really need these?
use Omnipay\Opayo\Message\ServerAuthorizeRequest;
use Omnipay\Opayo\Message\ServerCompleteAuthorizeRequest;
use Omnipay\Opayo\Message\ServerPurchaseRequest;
use Omnipay\Opayo\Message\ServerNotifyRequest;
use Omnipay\Opayo\Message\SharedTokenRemovalRequest;
use Omnipay\Opayo\Message\ServerTokenRegistrationRequest;
use Omnipay\Opayo\Message\ServerTokenRegistrationCompleteRequest;

/**
 * Opayo Server Gateway
 */
class ServerGateway extends DirectGateway
{
    public function getName()
    {
        return 'Opayo Server';
    }

    /**
     * Authorize a payment.
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest(ServerAuthorizeRequest::class, $parameters);
    }

    /**
     * Authorize and capture a payment.
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest(ServerPurchaseRequest::class, $parameters);
    }

    /**
     * Handle notification callback.
     * Replaces completeAuthorize() and completePurchase()
     */
    public function acceptNotification(array $parameters = array())
    {
        return $this->createRequest(ServerNotifyRequest::class, $parameters);
    }

    /**
     * Accept card details from a user and return a token, without any
     * authorization against that card.
     * i.e. standalone token creation.
     * Omnipay standard function; alias for registerToken()
     */
    public function createCard(array $parameters = array())
    {
        return $this->registerToken($parameters);
    }

    /**
     * Remove a card token from the account.
     * Standard Omnipay function.
     */
    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest(SharedTokenRemovalRequest::class, $parameters);
    }

    /**
     * Accept card details from a user and return a token, without any
     * authorization against that card.
     * i.e. standalone token creation.
     * Gateway-specific function.
     */
    public function registerToken(array $parameters = array())
    {
        return $this->createRequest(ServerTokenRegistrationRequest::class, $parameters);
    }

    /**
     * Handle authorize notification callback.
     * Please now use acceptNotification()
     * @deprecated
     */
    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest(ServerCompleteAuthorizeRequest::class, $parameters);
    }

    /**
     * Handle purchase notification callback.
     * Please now use acceptNotification()
     * @deprecated
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->completeAuthorize($parameters);
    }
}
