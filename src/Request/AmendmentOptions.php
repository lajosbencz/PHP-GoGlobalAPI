<?php

namespace GoGlobal\Request;

use GoGlobal\RequestInterface;

class AmendmentOptions extends BookingCancel implements RequestInterface
{

	static function getOperation() {
		return 'BOOKING_INFO_FOR_AMENDMENT_REQUEST';
	}

	static function getRequestType() {
		return 15;
	}

}