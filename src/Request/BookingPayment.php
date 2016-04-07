<?php

namespace GoGlobal\Request;

use GoGlobal\RequestAbstract;
use GoGlobal\RequestInterface;

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