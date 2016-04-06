<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use Travelhood\Library\Provider\GoGlobal\Helper;
use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;

class BookingCancel extends RequestAbstract implements RequestInterface
{
	protected $_bookingCode;

	static function getOperation() {
		return 'BOOKING_CANCEL_REQUEST';
	}

	static function getRequestType() {
		return 3;
	}

	public function setBookingCode($code) {
		$this->_bookingCode = $code;
		return $this;
	}

	public function getBookingCode() {
		return $this->_bookingCode;
	}

	public function getBody() {
		return Helper::wrapTag('GoBookingCode', $this->getBookingCode());
	}

	/**
	 * @return \Travelhood\Library\Provider\GoGlobal\Response\BookingCancel
	 */
	public function getResponse() {
		return parent::getResponse();
	}

}