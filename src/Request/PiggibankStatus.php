<?php

namespace GoGlobal\Request;

use GoGlobal\RequestAbstract;
use GoGlobal\RequestInterface;

class PiggibankStatus extends RequestAbstract implements RequestInterface
{
	// @TODO implement

	static function getOperation() {
		return 'PIGGIBANK_STATUS_REQUEST';
	}

	static function getRequestType() {
		return 12;
	}

	public function getBody() {
		return '';
	}

}