<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;

class HotelSearchGeo extends HotelSearch implements RequestInterface
{

	static function getOperation() {
		return 'HOTEL_SEARCH_REQUEST';
	}

	static function getRequestType() {
		return 11;
	}

	/**
	 * @return \Travelhood\Library\Provider\GoGlobal\Response\HotelSearchGeo
	 */
	public function getResponse() {
		return parent::getResponse();
	}

}