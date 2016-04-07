<?php

namespace GoGlobal\Request;

use GoGlobal\Helper;
use GoGlobal\RequestAbstract;
use GoGlobal\RequestInterface;

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
	 * @return \GoGlobal\Response\BookingCancel
	 */
	public function getResponse() {
		return parent::getResponse();
	}

}