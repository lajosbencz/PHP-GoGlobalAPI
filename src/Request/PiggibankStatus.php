<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;

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