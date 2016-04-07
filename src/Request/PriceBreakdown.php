<?php

namespace GoGlobal\Request;

use GoGlobal\RequestAbstract;
use GoGlobal\RequestInterface;

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