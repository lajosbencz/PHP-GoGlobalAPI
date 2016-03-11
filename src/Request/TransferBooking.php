<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;

class TransferBooking extends RequestAbstract implements RequestInterface
{
	// @TODO

	static function getOperation() {
		return 'TB_INSERT_REQUEST';
	}

	static function getRequestType() {
		return 19;
	}

	public function getBody() {
		return '';
	}

}