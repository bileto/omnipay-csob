<?php

namespace Omnipay\Csob\Message;

use Guzzle\Http\Message\RequestInterface as GuzzleRequestInterface;
use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

class ProcessPaymentResponse extends OmnipayAbstractResponse implements RedirectResponseInterface
{
    /** @var string */
    private $redirectUrl;

    function __construct(RequestInterface $request, $redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
        parent::__construct($request, null);
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * Gets the redirect target url.
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * Get the required redirect method (either GET or POST).
     */
    public function getRedirectMethod()
    {
        return GuzzleRequestInterface::GET;
    }

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     */
    public function getRedirectData()
    {
        return;
    }

    public function isRedirect()
    {
        return true;
    }


}