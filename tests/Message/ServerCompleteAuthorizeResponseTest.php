<?php

namespace Omnipay\Opayo\Message;

use Omnipay\Tests\TestCase;
use Mockery as m;

class ServerCompleteAuthorizeResponseTest extends TestCase
{
    public function testServerCompleteAuthorizeResponseSuccess()
    {
        $response = new ServerCompleteAuthorizeResponse(
            $this->getMockRequest(),
            array(
                'Status' => 'OK',
                'TxAuthNo' => 'b',
                'AVSCV2' => 'c',
                'AddressResult' => 'd',
                'PostCodeResult' => 'e',
                'CV2Result' => 'f',
                'GiftAid' => 'g',
                '3DSecureStatus' => 'h',
                'CAVV' => 'i',
                'AddressStatus' => 'j',
                'PayerStatus' => 'k',
                'CardType' => 'l',
                'Last4Digits' => 'm',
                'DeclineCode' => '00',
                'ExpiryDate' => '0722',
                'BankAuthCode' => '999777',
                //'VendorTxCode' => '123', <-- Not in response
            )
        );

        // The transaction ID is set in the original request only.

        $this->getMockRequest()->shouldReceive('getTransactionId')->once()->andReturn('123');
        $this->getMockRequest()->shouldReceive('getTransactionReference')->once()->andReturn('{"SecurityKey":"JEUPDN1N7E","TxAuthNo":"4255","VPSTxId":"{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}","VendorTxCode":"438791"}');

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('{"SecurityKey":"JEUPDN1N7E","TxAuthNo":"b","VPSTxId":"{F955C22E-F67B-4DA3-8EA3-6DAC68FA59D2}","VendorTxCode":"123"}', $response->getTransactionReference());
        $this->assertNull($response->getMessage());

        //$this->assertSame('123', $response->getTransactionId());
    }

    public function testFormCompleteAuthorizeResponseSuccess()
    {
        $response = new ServerCompleteAuthorizeResponse(
            $this->getMockRequest(),
            array(
                'Status' => 'OK',
                'TxAuthNo' => 'b',
                'AVSCV2' => 'c',
                'AddressResult' => 'd',
                'PostCodeResult' => 'e',
                'CV2Result' => 'f',
                'GiftAid' => 'g',
                '3DSecureStatus' => 'h',
                'CAVV' => 'i',
                'AddressStatus' => 'j',
                'PayerStatus' => 'k',
                'CardType' => 'l',
                'Last4Digits' => 'm',
                'DeclineCode' => '00',
                'ExpiryDate' => '0722',
                'BankAuthCode' => '999777',
                'VendorTxCode' => '123', // In response
            )
        );

        $this->assertSame('123', $response->getTransactionId());
    }

    public function testServerCompleteAuthorizeResponseFailure()
    {
        $response = new ServerCompleteAuthorizeresponse($this->getMockRequest(), array('Status' => 'INVALID'));

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testConfirm()
    {
        $response = m::mock('\Omnipay\Opayo\Message\ServerCompleteAuthorizeResponse')->makePartial();
        $response->shouldReceive('sendResponse')->once()->with('OK', 'https://www.example.com/', 'detail');

        $response->confirm('https://www.example.com/', 'detail');
    }

    public function testError()
    {
        $response = m::mock('\Omnipay\Opayo\Message\ServerCompleteAuthorizeResponse')->makePartial();
        $response->shouldReceive('sendResponse')->once()->with('ERROR', 'https://www.example.com/', 'detail');

        $response->error('https://www.example.com/', 'detail');
    }

    public function testInvalid()
    {
        $response = m::mock('\Omnipay\Opayo\Message\ServerCompleteAuthorizeResponse')->makePartial();
        $response->shouldReceive('sendResponse')->once()->with('INVALID', 'https://www.example.com/', 'detail');

        $response->invalid('https://www.example.com/', 'detail');
    }

    public function testSendResponse()
    {
        $response = m::mock('\Omnipay\Opayo\Message\ServerCompleteAuthorizeResponse')->makePartial();
        $response->shouldReceive('exitWith')->once()->with("Status=FOO\r\nRedirectUrl=https://www.example.com/");

        $response->sendResponse('FOO', 'https://www.example.com/');
    }

    public function testSendResponseDetail()
    {
        $response = m::mock('\Omnipay\Opayo\Message\ServerCompleteAuthorizeResponse')->makePartial();
        $response->shouldReceive('exitWith')->once()->with("Status=FOO\r\nRedirectUrl=https://www.example.com/\r\nStatusDetail=Bar");

        $response->sendResponse('FOO', 'https://www.example.com/', 'Bar');
    }
}
