<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;

class TransferSearch extends RequestAbstract implements RequestInterface
{
	// @TODO

	static function getOperation() {
		return 'TRANSFER_SEARCH_REQUEST';
	}

	static function getRequestType() {
		return 17;
	}

	public function getBody() {
		return '';
	}

}