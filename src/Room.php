<?php

namespace Travelhood\Library\Provider\GoGlobal;

use Travelhood\Library\Provider\GoGlobal\Enum\Room as RoomEnum;

class Room {

	const CHILD_AGE = 8;
	const CHILD_AGE_MIN = 2;
	const CHILD_AGE_MAX = 10;
	const CHILD_COUNT_MAX = 4;
	const ADULT_COUNT_MAX = 5;
	const ROOM_COUNT_MAX = 3;

	protected $_adults = [];
	protected $_children = [];
	protected $_infant = false;
	protected $_countAdults = 0;
	protected $_countChildren;
	protected $_roomType = false;
	protected $_validForBooking = true;

	public function __construct($adults=0, $children=0, $infant=false) {
		$this->clear();
		for($i=0; $i<$adults; $i++) $this->addAdult();
		for($i=0; $i<$children; $i++) $this->addChild();
		$this->setInfant($infant);
	}

	public function clear() {
		$this->_adults = [];
		$this->_children = [];
		$this->_infant = false;
		$this->_countAdults = 0;
		$this->_countChildren = 0;
	}

	public function addAdult($first_name=null, $last_name=null) {
        if($first_name===null || $last_name===null) {
            $this->_validForBooking = false;
        }
		$this->_adults[] = ['first_name'=>$first_name,'last_name'=>$last_name];
		$this->_countAdults++;
		return $this;
	}

	public function addChild($age=self::CHILD_AGE, $first_name=null, $last_name=null) {
        if($first_name===null || $last_name===null) {
            $this->_validForBooking = false;
        }
		$this->_children[] = ['first_name'=>$first_name, 'last_name'=>$last_name, 'age'=>max(self::CHILD_AGE_MIN,min(self::CHILD_AGE_MAX,$age))];
		$this->_countChildren++;
		return $this;
	}

	public function setInfant($infant=true) {
		$this->_infant = $infant;
		return $this;
	}

	public function setRoomType($type) {
		$this->_roomType = $type;
		if(RoomEnum::isValidName($type)) {
			$type = RoomEnum::$type;
		}
		if(!RoomEnum::isValidValue($type)) {
			throw new Exception("Invalid Room type: {$type}");
		}
		$this->_roomType = $type;
		return $this;
	}

	public function countAdults() {
		return $this->_countAdults;
	}

	public function countChildren() {
		return $this->_countChildren;
	}

	public function countPersons() {
		return $this->countAdults() + $this->countChildren();
	}

	public function getAdult($i) {
		if($i<0 || $i>=$this->countAdults()) {
			return false;
		}
		return $this->_adults[$i];
	}

	public function getAdultName($i) {
		$a = $this->getAdult($i);
		if(!$a) return false;
        $name = trim($a['first_name']) . ' ' .trim($a['last_name']);
        if(!preg_match('/((MR\.?)|(MRS\.?)|MISS)$/i',$name)) {
            $name.=' MR.';
        }
        elseif(preg_match('/(MR|MRS)$/i',$name)) {
            $name.= '.';
        }
		return $name;
	}

	public function getChild($i) {
		if($i<0 || $i>=$this->countChildren()) {
			return false;
		}
		return $this->_children[$i];
	}

	public function getChildName($i) {
		$a = $this->getChild($i);
		if(!$a) return false;
		return $a['first_name'] . ' ' .$a['last_name'];
	}

	public function getChildAge($i) {
		$a = $this->getChild($i);
		if(!$a) return false;
		return $a['age'];
	}

	public function getInfant() {
		return $this->_infant?1:0;
	}

	public function getRoomType() {
		return $this->_roomType;
	}

	public function isValidForBooking() {
		return $this->_validForBooking;
	}

	public function xmlForSearch() {
		$xml = "<Room Adults=\"{$this->countAdults()}\" CotCount=\"{$this->getInfant()}\">";
		for($c=0; $c<$this->countChildren(); $c++) {
			$xml.= "<ChildAge>{$this->getChildAge($c)}</ChildAge>";
		}
		$xml.= '</Room>';
		return $xml;
	}

	public function xmlForBooking($roomId=1, $personStartId=1) {
		if(!$this->isValidForBooking()) {
			throw new Exception("The room must be set up with traveler names for booking");
		}
		$pid = $personStartId;
		$xml = '<RoomType Adults="'.$this->countAdults().'"';
		if($this->getInfant()>0) {
			$xml.= ' CotCount="'.$this->getInfant().'"';
		}
		$xml.= '>';
		$xml.= '<Room RoomID="'.$roomId.'">';
		for($a=0; $a<$this->countAdults(); $a++) {
			$xml.= '<PersonName PersonID="'.$pid.'">'.strtoupper(stripAccents($this->getAdultName($a))).'</PersonName>';
			$pid++;
		}
		for($c=0; $c<$this->countChildren(); $c++) {
			$xml.= '<ExtraBed PersonID="'.$pid.'" ChildAge="'.$this->getChildAge($c).'">'.strtoupper(stripAccents($this->getChildName($c))).'</ExtraBed>';
			$pid++;
		}
		$xml.= '</Room>';
		$xml.= '</RoomType>';
		return $xml;
	}

	public function toString() {
		return $this->xmlForSearch();
	}

	public function __toString() {
		return $this->toString();
	}

}