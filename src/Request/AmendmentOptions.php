<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;

class AmendmentOptions extends BookingCancel implements RequestInterface
{

	static function getOperation() {
		return 'BOOKING_INFO_FOR_AMENDMENT_REQUEST';
	}

	static function getRequestType() {
		return 15;
	}

}