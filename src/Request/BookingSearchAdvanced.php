<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use DateTime;
use Travelhood\Library\Provider\GoGlobal\Exception;
use Travelhood\Library\Provider\GoGlobal\Helper;
use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;

class BookingSearchAdvanced extends RequestAbstract implements RequestInterface
{
	protected $_dateFrom = null;
	protected $_nights = null;
	protected $_dateUntil = null;
	protected $_passengerName = null;
	protected $_city = null;
	protected $_hotelName = null;

	static function getOperation() {
		return 'ADV_BOOKING_SEARCH_REQUEST';
	}

	static function getRequestType() {
		return 10;
	}


	public function setDateFrom($date) {
		$this->_dateFrom = $date;
		return $this;
	}

	public function getDateFrom() {
		return $this->_dateFrom;
	}

	public function setDateUntil($date) {
		$this->_dateUntil = $date;
		return $this;
	}

	public function getDateUntil() {
		return $this->_dateUntil;
	}

	public function setNights($nights=1) {
		$this->_nights = max(1,$nights);
		return $this;
	}

	public function getNights() {
		return $this->_nights;
	}

	public function setPassengerName($name) {
		$this->_passengerName = $name;
		return $this;
	}

	public function getPassengerName() {
		return $this->_passengerName;
	}

	public function setCity($city) {
		$this->_city = intval($city);
		return $this;
	}

	public function getCity() {
		return $this->_city;
	}

	public function setHotelName($name) {
		$this->_hotelName = $name;
		return $this;
	}

	public function getHotelName() {
		return $this->_hotelName;
	}

	public function getBody() {
		$xml  = "";
		$xml .= ($this->getDateFrom()) ? Helper::wrapTag('ArrivalDateRangeFrom', $this->getDateFrom()) : "";
		$xml .= ($this->getDateUntil()) ? Helper::wrapTag('ArrivalDateRangeTo', $this->getDateUntil()) : "";
		$xml .= ($this->getNights()) ? Helper::wrapTag('Nights', $this->getNights()) : "";
		$xml .= ($this->getPassengerName()) ? Helper::wrapTag('PaxName', $this->getPassengerName()) : "";
		$xml .= ($this->getCity()) ? Helper::wrapTag('CityCode', $this->getCity()) : "";
		$xml .= ($this->getHotelName()) ? Helper::wrapTag('HotelName', $this->getHotelName()) : "";
		return $xml;
	}

	/**
	 * @return \Travelhood\Library\Provider\GoGlobal\Response\BookingSearchAdvanced
	 */
	public function getResponse() {
		return parent::getResponse();
	}

}