<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;

class BookingPayment extends RequestAbstract implements RequestInterface
{

	// @TODO implement when account activated

	static function getOperation() {
		return 'BOOKING_PAYMENT_REQUEST';
	}

	static function getRequestType() {
		return 13;
	}

	public function getBody() {
		return '';
	}

}