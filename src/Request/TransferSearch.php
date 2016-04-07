<?php

namespace GoGlobal\Request;

use GoGlobal\Enum\Language;
use GoGlobal\Exception;
use GoGlobal\Helper;
use GoGlobal\RequestAbstract;
use GoGlobal\RequestInterface;

class TransferSearch extends RequestAbstract implements RequestInterface
{
	/** @var string */
	protected $_date;
	protected $_PickUpType_IN = true;
	protected $_PassengersNumber;
	protected $_CityCode;
	protected $_AirportCode;
	protected $_HotelCode;
	protected $_Currency;
	protected $_DriverLanguage=Language::ENGLISH;

	static function getOperation() {
		return 'TRANSFER_SEARCH_REQUEST';
	}

	static function getRequestType() {
		return 17;
	}


	/**
	 * @return string
	 */
	public function getDate()
	{
		return $this->_date;
	}

	/**
	 * @param string $date
	 * @return $this
	 */
	public function setDate($date)
	{
		$this->_date = $date;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function getPickUpTypeIn()
	{
		$this->_PickUpType_IN = true;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function setPickUpTypeOut()
	{
		$this->_PickUpType_IN = false;
		return $this;
	}

	/**
	 * @return integer
	 */
	public function getPassengersNumber()
	{
		return $this->_PassengersNumber;
	}

	/**
	 * @param integer $PassengersNumber
	 * @return $this
	 */
	public function setPassengersNumber($PassengersNumber)
	{
		$this->_PassengersNumber = $PassengersNumber;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCityCode()
	{
		return $this->_CityCode;
	}

	/**
	 * @param string $CityCode
	 * @return $this
	 */
	public function setCityCode($CityCode)
	{
		$this->_CityCode = $CityCode;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAirportCode()
	{
		return $this->_AirportCode;
	}

	/**
	 * @param string $AirportCode
	 * @return $this
	 */
	public function setAirportCode($AirportCode)
	{
		$this->_AirportCode = $AirportCode;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getHotelCode()
	{
		return $this->_HotelCode;
	}

	/**
	 * @param string $HotelCode
	 * @return $this
	 */
	public function setHotelCode($HotelCode)
	{
		$this->_HotelCode = $HotelCode;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCurrency()
	{
		return $this->_Currency;
	}

	/**
	 * @param string $Currency
	 * @return $this
	 */
	public function setCurrency($Currency)
	{
		$this->_Currency = $Currency;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDriverLanguage()
	{
		return $this->_DriverLanguage;
	}

	/**
	 * @param string $DriverLanguage
	 * @return $this
	 * @throws Exception
	 */
	public function setDriverLanguage($DriverLanguage)
	{
		if(!Language::isValidValue($DriverLanguage)) {
			throw new Exception('Invalid driver language: '.$DriverLanguage);
		}
		$this->_DriverLanguage = $DriverLanguage;
		return $this;
	}


	public function getBody() {
		$xml = "";
		if ($this->_PickUpType_IN) {  //reptérről befelé

			$xml.=Helper::WrapTag('PickUpType','Airport');
			$xml.=Helper::WrapTag('PickUpCode',$this->_AirportCode);
			$xml.=Helper::WrapTag('DropOffType','Accomodation');
			if ($this->_HotelCode<>"") {
				$xml .= Helper::WrapTag('DropOffCode', $this->_HotelCode, ['type' => "hotel"]);
			}
			else {
				$xml.= Helper::WrapTag('DropOffCode',$this->_CityCode,['type'=>"city"]);
			}
		}
		else {	//reptérre kifelé

			$xml.= Helper::WrapTag('PickUpType','Accomodation');
			if ($this->_HotelCode<>"") {
				$xml .= Helper::WrapTag('PickUpCode', $this->_HotelCode, ['type' => "hotel"]);
			}
			else {
				$xml.=Helper::WrapTag('PickUpCode',$this->_CityCode,['type' => "city"]);
			}
			$xml.=Helper::WrapTag('DropOffType','Airport');
			$xml.= Helper::WrapTag('DropOffCode',$this->_AirportCode);
		}
		$xml.= Helper::WrapTag('ArrivalDate',$this->_date);
		$xml.= Helper::WrapTag('Passengers',$this->_PassengersNumber);
		$xml.= Helper::WrapTag('Currency',$this->_Currency);
		$xml.= Helper::WrapTag('Language',$this->_DriverLanguage);

		return $xml;
	}
}