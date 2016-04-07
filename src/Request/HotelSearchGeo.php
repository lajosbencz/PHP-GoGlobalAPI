<?php

namespace GoGlobal\Request;

use GoGlobal\RequestInterface;

class HotelSearchGeo extends HotelSearch implements RequestInterface
{

	static function getOperation() {
		return 'HOTEL_SEARCH_REQUEST';
	}

	static function getRequestType() {
		return 11;
	}

	/**
	 * @return \GoGlobal\Response\HotelSearchGeo
	 */
	public function getResponse() {
		return parent::getResponse();
	}

}