<?php

namespace GoGlobal\Request;

use GoGlobal\RequestInterface;

class BookingSearch extends BookingCancel implements RequestInterface
{

	static function getOperation() {
		return 'BOOKING_SEARCH_REQUEST';
	}

	static function getRequestType() {
		return 4;
	}

	/**
	 * @return \GoGlobal\Response\BookingSearch
	 */
	public function getResponse() {
		return parent::getResponse();
	}

}