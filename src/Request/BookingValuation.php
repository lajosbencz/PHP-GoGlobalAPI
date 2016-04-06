<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use Travelhood\Library\Provider\GoGlobal\Helper;
use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;

class BookingValuation extends RequestAbstract implements RequestInterface
{
	protected $_hotelSearchCode = null;
	protected $_dateFrom = null;

	static function getOperation() {
		return 'BOOKING_VALUATION_REQUEST';
	}

	static function getRequestType() {
		return 9;
	}

	public function setHotelSearchCode($code) {
		$this->_hotelSearchCode = $code;
		return $this;
	}

	public function getHotelSearchCode() {
		return $this->_hotelSearchCode;
	}

	public function setDateFrom($date) {
		$this->_dateFrom = $date;
		return $this;
	}

	public function getDateFrom() {
		return $this->_dateFrom;
	}

	public function getBody() {
		$xml = '';
		$xml.= Helper::wrapTag('HotelSearchCode',$this->getHotelSearchCode());
		$xml.= Helper::wrapTag('ArrivalDate',$this->getDateFrom());
		return $xml;
	}

	/**
	 * @return \Travelhood\Library\Provider\GoGlobal\Response\BookingValuation
	 */
	public function getResponse() {
		return parent::getResponse();
	}

}