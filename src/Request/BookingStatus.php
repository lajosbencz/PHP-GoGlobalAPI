<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use Travelhood\Library\Provider\GoGlobal\Helper;
use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;

class BookingStatus extends RequestAbstract implements RequestInterface
{
	protected $_bookingCodes = [];

	static function getOperation() {
		return 'HOTEL_INFO_REQUEST';
	}

	static function getRequestType() {
		return 5;
	}

	public function addBookingCode($code) {
		if(!is_array($code)) {
			$this->_bookingCodes[] = $code;
		}
		else {
			foreach($code as $c) {
				$this->addBookingCode($c);
			}
		}
		return $this;
	}

	public function getBookingCodes() {
		return $this->_bookingCodes;
	}

	public function getBody() {
		$xml = "";
		foreach($this->getBookingCodes() as $code) {
			$xml .= Helper::wrapTag('GoBookingCode', $code);
		}
		return $xml;
	}

	/**
	 * @return \Travelhood\Library\Provider\GoGlobal\Response\BookingStatus
	 */
	public function getResponse() {
		return parent::getResponse();
	}

}