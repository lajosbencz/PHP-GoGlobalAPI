<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;

class HotelInfoGeo extends HotelInfo implements RequestInterface
{

	static function getOperation() {
		return 'HOTEL_INFO_REQUEST';
	}

	static function getRequestType() {
		return 61;
	}

	/**
	 * @return \Travelhood\Library\Provider\GoGlobal\Response\HotelInfoGeo
	 */
	public function getResponse() {
		return parent::getResponse();
	}

}