<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\PaymentExpress;

use SimpleXMLElement;
use Tala\AbstractResponse;
use Tala\Exception;
use Tala\Exception\InvalidResponseException;

/**
 * DPS PaymentExpress PxPost Response
 */
class Response extends AbstractResponse
{
    public function __construct($data)
    {
        if (empty($data)) {
            throw new InvalidResponseException;
        }

        $this->data = new SimpleXMLElement($data);
    }

    public function isSuccessful()
    {
        return 1 === (int) $this->data->Success;
    }

    public function getGatewayReference()
    {
        return (string) $this->data->DpsTxnRef;
    }

    public function getMessage()
    {
        if (isset($this->data->HelpText)) {
            return (string) $this->data->HelpText;
        } else {
            return (string) $this->data->ResponseText;
        }
    }
}
