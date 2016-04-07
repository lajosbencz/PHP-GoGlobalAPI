<?php

namespace GoGlobal\Request;

use GoGlobal\Helper;
use GoGlobal\RequestAbstract;
use GoGlobal\RequestInterface;


class TransferBooking extends RequestAbstract implements RequestInterface
{

	protected $_PickUpType_IN = true;
	protected $_SearchID;
	protected $_ItemCode;
	protected $_AgencyReference;
	protected $_VehicleID;
	protected $_PassengersNum;
	protected $_PassengerName;
	protected $_PickupTime;
	protected $_FlightNumber;
	protected $_TargetCityID;
	protected $_HotelName;
	protected $_HotelAddress;
	protected $_Remarks;


	static function getOperation() {
		return 'TB_INSERT_REQUEST';
	}

	static function getRequestType() {
		return 19;
	}

	/**
	 * @return $this
	 */
	public function getPickUpTypeIN()
	{
		$this->_PickUpType_IN = true;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function setPickUpTypeOUT()
	{
		$this->_PickUpType_IN = false;
		return $this;
	}

	/**
	 * @return integer
	 */
	public function getSearchID()
	{
		return $this->_SearchID;
	}

	/**
	 * @param integer $SearchID
	 * @return $this
	 */
	public function setSearchID($SearchID)
	{
		$this->_SearchID = $SearchID;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getItemCode()
	{
		return $this->_ItemCode;
	}

	/**
	 * @param mixed $ItemCode
	 * @return $this
	 */
	public function setItemCode($ItemCode)
	{
		$this->_ItemCode = $ItemCode;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAgencyReference()
	{
		return $this->_AgencyReference;
	}

	/**
	 * @param string $AgencyReference
	 * @return $this
	 */
	public function setAgencyReference($AgencyReference)
	{
		$this->_AgencyReference = $AgencyReference;
		return $this;
	}

	/**
	 * @return integer
	 */
	public function getVehicleID()
	{
		return $this->_VehicleID;
	}

	/**
	 * @param integer $VehicleID
	 * @return $this
	 */
	public function setVehicleID($VehicleID)
	{
		$this->_VehicleID = $VehicleID;
		return $this;
	}

	/**
	 * @return integer
	 */
	public function getPassengersNum()
	{
		return $this->_PassengersNum;
	}

	/**
	 * @param integer $PassengersNum
	 * @return $this
	 */
	public function setPassengersNum($PassengersNum)
	{
		$this->_PassengersNum = $PassengersNum;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassengerName()
	{
		return $this->_PassengerName;
	}

	/**
	 * @param string $PassengerName
	 * @return $this
	 */
	public function setPassengerName($PassengerName)
	{
		$this->_PassengerName = $PassengerName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPickupTime()
	{
		return $this->_PickupTime;
	}

	/**
	 * @param string $PickupTime
	 * @return $this
	 */
	public function setPickupTime($PickupTime)
	{
		$this->_PickupTime = $PickupTime;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFlightNumber()
	{
		return $this->_FlightNumber;
	}

	/**
	 * @param string $FlightNumber
	 * @return $this
	 */
	public function setFlightNumber($FlightNumber)
	{
		$this->_FlightNumber = $FlightNumber;
		return $this;
	}

	/**
	 * @return integer
	 */
	public function getTargetCityID()
	{
		return $this->_TargetCityID;
	}

	/**
	 * @param integer $TargetCityID
	 * @return $this
	 */
	public function setTargetCityID($TargetCityID)
	{
		$this->_TargetCityID = $TargetCityID;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getHotelName()
	{
		return $this->_HotelName;
	}

	/**
	 * @param string $HotelName
	 * @return $this
	 */
	public function setHotelName($HotelName)
	{
		$this->_HotelName = $HotelName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getHotelAddress()
	{
		return $this->_HotelAddress;
	}

	/**
	 * @param string $HotelAddress
	 * @return TransferBooking
	 */
	public function setHotelAddress($HotelAddress)
	{
		$this->_HotelAddress = $HotelAddress;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getRemarks()
	{
		return $this->_Remarks;
	}

	/**
	 * @param string $Remarks
	 * @return TransferBooking
	 */
	public function setRemarks($Remarks)
	{
		$this->_Remarks = $Remarks;
		return $this;
	}



	public function getBody() {
		$xml = "";
		$xml.=Helper::WrapTag('searchID',$this->_SearchID);
		$xml.=Helper::WrapTag('ItemCode',$this->_ItemCode);
		$xml.=Helper::WrapTag('VehicleID',$this->_VehicleID);
		$xml.=Helper::WrapTag('PickupTime',$this->_PickupTime);

		if ($this->_PickUpType_IN) { //reptérről befelé

			$xml.=Helper::WrapTag('ArrivingCityID',$this->_TargetCityID);
			$xml.=Helper::WrapTag('PickupFlightNumber',$this->_FlightNumber);
			$xml.=Helper::WrapTag('DropOffHotel',$this->_HotelName);
			$xml.=Helper::WrapTag('DropOffAddress',$this->_HotelAddress);
		}
		else { //szállásról a reptérre

			$xml.=Helper::WrapTag('DropOffTime',$this->_PickupTime);
			$xml.=Helper::WrapTag('PickupHotel',$this->_HotelName);
			$xml.=Helper::WrapTag('PickupAddress',$this->_HotelAddress);
			$xml.=Helper::WrapTag('DropOffFlightNumber',$this->_FlightNumber);
			$xml.=Helper::WrapTag('DepartingToCityID',$this->_TargetCityID);
		}

		$xml.=Helper::WrapTag('PassengersNum',$this->_PassengersNum);
		$xml.=Helper::WrapTag('PassengerName',$this->_PassengerName);
		$xml.=Helper::WrapTag('Remarks',$this->_Remarks);
		$xml.=Helper::WrapTag('AgencyReference',$this->_AgencyReference);

		return $xml;
	}

}