<?php

namespace GoGlobal\Request;

use GoGlobal\RequestAbstract;
use GoGlobal\RequestInterface;

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
