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

use Tala\Exception;
use Tala\Exception\InvalidResponseException;

/**
 * Pin Response
 */
class Response extends \Tala\Response
{
    public function __construct($data)
    {
        $this->data = json_decode($data);

        if (empty($this->data)) {
            throw new InvalidResponseException;
        } elseif (isset($this->data->error)) {
            throw new Exception($this->data->error_description);
        }

        $this->message = $this->data->response->status_message;
        $this->gatewayReference = $this->data->response->token;
    }
}
