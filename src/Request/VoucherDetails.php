<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use Travelhood\Library\Provider\GoGlobal\Helper;
use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;

class VoucherDetails extends RequestAbstract implements RequestInterface
{
	protected $_bookingCode;
	protected $_emergencyPhone = true;

	static function getOperation() {
		return 'VOUCHER_DETAILS_REQUEST';
	}

	static function getRequestType() {
		return 8;
	}

	public function setBookingCode($code) {
		$this->_bookingCode = $code;
		return $this;
	}

	public function getBookingCode() {
		return $this->_bookingCode;
	}

	public function setEmergencyPhone($show=true) {
		$this->_emergencyPhone = $show;
		return $this;
	}

	public function getEmergencyPhone() {
		return $this->_emergencyPhone;
	}

	public function getBody() {
		$xml = '';
		$xml.= Helper::wrapTag('GoBookingCode', $this->getBookingCode());
		$xml.= Helper::wrapTag('GetEmergencyPhone', $this->getEmergencyPhone()?'true':'false');
		return $xml;
	}

	/**
	 * @return \Travelhood\Library\Provider\GoGlobal\Response\VoucherDetails
	 */
	public function getResponse() {
		return parent::getResponse();
	}

}