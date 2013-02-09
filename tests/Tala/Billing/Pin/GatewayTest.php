<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\Pin;

use Mockery as m;
use Tala\BaseGatewayTest;
use Tala\CreditCard;
use Tala\Request;

class GatewayTest extends BaseGatewayTest
{
    public function setUp()
    {
        $this->httpClient = m::mock('\Tala\HttpClient\HttpClientInterface');
        $this->httpRequest = m::mock('\Symfony\Component\HttpFoundation\Request');

        $this->gateway = new Gateway($this->httpClient, $this->httpRequest);

        $this->options = array(
            'amount' => 1000,
            'card' => new CreditCard(array(
                'firstName' => 'Example',
                'lastName' => 'User',
                'number' => '4111111111111111',
                'expiryMonth' => '12',
                'expiryYear' => '2016',
                'cvv' => '123',
            )),
        );
    }

    /**
     * @expectedException Tala\Exception
     * @expectedExceptionMessage A description of the error.
     */
    public function testPurchaseError()
    {
        $this->httpRequest->shouldReceive('getClientIp')->once()->andReturn('127.0.0.1');

        $this->httpClient->shouldReceive('post')->once()
            ->with('https://api.pin.net.au/1/charges', m::type('array'), m::type('array'))
            ->andReturn('{"error":"standard_error_name","error_description":"A description of the error."}');

        $this->gateway->purchase($this->options);
    }

    public function testPurchaseSuccess()
    {
        $this->httpRequest->shouldReceive('getClientIp')->once()->andReturn('127.0.0.1');

        $this->httpClient->shouldReceive('post')->once()
            ->with('https://api.pin.net.au/1/charges', m::type('array'), m::type('array'))
            ->andReturn('{"response":{"token":"ch_lfUYEBK14zotCTykezJkfg","status_message":"Success!"}}');

        $response = $this->gateway->purchase($this->options);

        $this->assertEquals('ch_lfUYEBK14zotCTykezJkfg', $response->getGatewayReference());
    }
}
