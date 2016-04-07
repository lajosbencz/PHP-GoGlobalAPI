<?php

namespace GoGlobal\Request;

use GoGlobal\Helper;
use GoGlobal\RequestAbstract;
use GoGlobal\RequestInterface;

class HotelInfo extends RequestAbstract implements RequestInterface
{
	protected $_hotelSearchCode;

	static function getOperation() {
		return 'HOTEL_INFO_REQUEST';
	}

	static function getRequestType() {
		return 6;
	}

	public function setHotelSearchCode($code) {
		$this->_hotelSearchCode = $code;
		return $this;
	}

	public function getHotelSearchCode() {
		return $this->_hotelSearchCode;
	}

	public function getBody() {
		return Helper::wrapTag('HotelSearchCode', $this->getHotelSearchCode());
	}

	/**
	 * @return \GoGlobal\Response\HotelInfo
	 */
	public function getResponse() {
		return parent::getResponse();
	}

}