<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;

class AmendmentRequest extends RequestAbstract implements RequestInterface
{

	// @TODO implement

	static function getOperation() {
		return 'BOOKING_AMENDMENT_REQUEST';
	}

	static function getRequestType() {
		return 16;
	}

	public function getBody() {
		return '';
	}

}
