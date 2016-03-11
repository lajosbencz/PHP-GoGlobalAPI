<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use DateTime;
use Travelhood\Library\Provider\GoGlobal\Exception;
use Travelhood\Library\Provider\GoGlobal\Helper;
use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;
use Travelhood\Library\Provider\GoGlobal\Room;

class BookingInsert extends RequestAbstract implements RequestInterface
{
	protected $_reference = 0;
	protected $_hotelSearchCode = false;
	protected $_dateFrom = null;
	protected $_nights = 1;
	protected $_dateUntil = null;
	protected $_alternative = 0;
	protected $_leader = 1;
	/** @var Room[] */
	protected $_rooms = [];

	static function getOperation() {
		return 'BOOKING_INSERT_REQUEST';
	}

	static function getRequestType() {
		return 2;
	}

	public function setReference($ref) {
		$this->_reference = $ref;
		return $this;
	}

	public function getReference() {
		return $this->_reference;
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
		if($this->_nights) {
			$this->_dateUntil = date('Y-m-d',strtotime($this->_dateFrom.' +'.$this->_nights.' days'));
		}
		return $this;
	}

	public function getDateFrom() {
		return $this->_dateFrom;
	}

	public function setDateUntil($date) {
		if(!$this->_dateFrom) throw new Exception("Arrival date must be set when using departure date!");
		$this->_dateUntil = $date;
		$d1 = new DateTime($this->_dateFrom);
		$d2 = new DateTime($this->_dateUntil);
		$d = $d1->diff($d2);
		$this->setNights(abs($d->days));
		return $this;
	}

	public function getDateUntil() {
		return $this->_dateUntil;
	}

	public function setNights($nights=1) {
		$this->_nights = max(1,$nights);
		if($this->_dateFrom) {
			$this->_dateUntil = date('Y-m-d',strtotime($this->_dateFrom.' +'.$this->_nights.' days'));
		}
		return $this;
	}

	public function getNights() {
		return $this->_nights;
	}

	public function setAlternative($allow=true) {
		$this->_alternative = $allow;
		return $this;
	}

	public function getAlternative() {
		return $this->_alternative;
	}

	public function setLeader($leader=1) {
		$this->_leader = max(1,$leader);
		return $this;
	}

	public function getLeader() {
		return $this->_leader;
	}

	/**
	 * @param Room|array $room
	 * @return $this
	 */
	public function addRoom($room) {
		if(is_array($room)) {
			$adults = func_get_arg(0);
			$children = func_get_arg(1);
			$room = new Room();
			foreach($adults as $a) {
				$room->addAdult(($a));
			}
			if(is_array($children)) foreach($children as $c) {
				if(array_key_exists('age',$c) && array_key_exists('name',$c)) {
					$room->addChild($c['age'],($c['name']));
				}
				else {
					$room->addChild($c[0],($c[1]));
				}
			}
			$room->setInfant(func_get_arg(2));
		}
		$this->_rooms[] = $room;
		return $this;
	}

	public function clearRooms() {
		$this->_rooms = [];
		return $this;
	}

	public function countRooms() {
		return count($this->_rooms);
	}

	/**
	 * 1 BASED!
	 * @param $n
	 * @return Room
	 */
	public function getRoom($n) {
		return $this->_rooms[max(0,min($this->countRooms()-1,$n-1))];
	}

	public function getRooms() {
		return $this->_rooms;
	}

	public function getBody() {
		$xml  = "";
		$xml .= Helper::wrapTag('AgentReference', $this->getReference());
		$xml .= Helper::wrapTag('HotelSearchCode', $this->getHotelSearchCode());
		$xml .= Helper::wrapTag('ArrivalDate', $this->getDateFrom());
		$xml .= Helper::wrapTag('Nights', $this->getNights());
		$xml .= Helper::wrapTag('NoAlternativeHotel', $this->getAlternative()?'false':'true');
		$xml .= Helper::wrapTag('Leader', false, ['LeaderPersonID' => $this->getLeader()]);
		$rooms = '';
		$pid = 1;
		for($r=1; $r<=$this->countRooms(); $r++) {
			$room = $this->getRoom($r);
			$rooms.= $room->xmlForBooking($r, $pid);
			$pid+=$room->countPersons();
		}
		$xml .= Helper::wrapTag('Rooms', $rooms);
		return $xml;
	}

	/**
	 * @return \Travelhood\Library\Provider\GoGlobal\Response\BookingInsert
	 */
	public function getResponse() {
		return parent::getResponse();
	}

}