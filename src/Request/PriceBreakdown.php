<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;

class PriceBreakdown extends RequestAbstract implements RequestInterface
{
	// @TODO implement

	static function getOperation() {
		return 'PRICE_BREAKDOWN_REQUEST';
	}

	static function getRequestType() {
		return 14;
	}

	public function getBody() {
		return '';
	}

}