<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;

class BookingSearch extends BookingCancel implements RequestInterface
{

	static function getOperation() {
		return 'BOOKING_SEARCH_REQUEST';
	}

	static function getRequestType() {
		return 4;
	}

	/**
	 * @return \Travelhood\Library\Provider\GoGlobal\Response\BookingSearch
	 */
	public function getResponse() {
		return parent::getResponse();
	}

}