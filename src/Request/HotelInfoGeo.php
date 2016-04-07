<?php

namespace GoGlobal\Request;

use GoGlobal\RequestInterface;

class HotelInfoGeo extends HotelInfo implements RequestInterface
{

	static function getOperation() {
		return 'HOTEL_INFO_REQUEST';
	}

	static function getRequestType() {
		return 61;
	}

	/**
	 * @return \GoGlobal\Response\HotelInfoGeo
	 */
	public function getResponse() {
		return parent::getResponse();
	}

}