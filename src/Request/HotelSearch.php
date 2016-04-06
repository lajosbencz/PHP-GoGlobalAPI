<?php

namespace Travelhood\Library\Provider\GoGlobal\Request;

use DateTime;
use Travelhood\Library\Provider\GoGlobal\Exception;
//use Travelhood\Library\Provider\GoGlobal\Service;
use Travelhood\Library\Provider\GoGlobal\Helper;
//use Travelhood\Library\Provider\GoGlobal\Enum\Room as RoomEnum;
use Travelhood\Library\Provider\GoGlobal\Enum\Sort as SortEnum;
use Travelhood\Library\Provider\GoGlobal\Enum\Category as CategoryEnum;
use Travelhood\Library\Provider\GoGlobal\RequestAbstract;
use Travelhood\Library\Provider\GoGlobal\RequestInterface;
use Travelhood\Library\Provider\GoGlobal\Room;

class HotelSearch extends RequestAbstract implements RequestInterface
{

	static function getOperation() {
		return 'HOTEL_SEARCH_REQUEST';
	}

	static function getRequestType() {
		return 1;
	}

	protected $_sort = SortEnum::PRICE_ASC;
	protected $_city = 0;
	protected $_hotelCode = null;
	protected $_dateFrom = null;
	protected $_nights = null;
	protected $_dateUntil = null;
	protected $_categories = [];
	protected $_rooms = [];
	protected $_apartments = 'false';


	public function setSort($sort) {
		$this->_sort = $sort;
		return $this;
	}

	public function getSort() {
		return $this->_sort;
	}

	public function setCity($city) {
		$this->_city = intval($city);
		return $this;
	}

	public function getCity() {
		return $this->_city;
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

	public function setCategories($categories) {
		$this->_categories = [];
		if(!is_array($categories)) $categories = func_get_args();
		foreach($categories as $c) {
			$this->_categories[] = CategoryEnum::fromFloat($c);
		}
		return $this;
	}

	public function getCategories() {
		return $this->_categories;
	}

	public function setApartments($allow=true) {
		$this->_apartments = $allow?'true':'false';
		return $this;
	}

	public function getApartments() {
		return $this->_apartments=='true';
	}

	public function setHotelCode($hotelCode) {
		$this->_hotelCode = $hotelCode;
		return $this;
	}

	public function getHotelCode() {
		return $this->_hotelCode?:null;
	}

	public function addRoom($adults=2, $children=0, $infant=false) {
		if(count($this->_rooms)>=Room::ROOM_COUNT_MAX) throw new Exception("Max allowed rooms: ".Room::ROOM_COUNT_MAX);
		$this->_rooms[] = [
			'adults' => $adults,
			'children' => $children,
			'infant' => $infant?1:0,
		];
		return $this;
	}

	protected function getLastRoomIndex() {
		$c = count($this->_rooms);
		return $c-1;
	}

	public function getRooms() {
		return $this->_rooms;
	}

	public function clearRooms() {
		$this->_rooms = [];
	}

	public function getAdultCount() {
		$c = 0;
		foreach($this->_rooms as $r) $c+=$r['adults'];
		return $c;
	}

	public function getChildrenCount() {
		$c = 0;
		foreach($this->_rooms as $r) $c+=$r['children'];
		return $c;
	}

	public function getHash() {
		$s = 'c:'.$this->_city.';';
		$s.= 'f:'.$this->_dateFrom.';';
		$s.= 'n:'.$this->_nights.';';
		$s.= 'a:'.$this->_apartments.';';
		foreach($this->_rooms as $r) {
			foreach($r as $rk=>$rv) {
				$s.= 'r'.$rk.':'.$rk.$rv.';';
			}
		}
		return sha1($s);
	}

	public function getBody() {
		if($this->_city<1 && $this->_hotelCode<1) throw new Exception("CityCode or HotelId must be specified!");
		if($this->_dateFrom<1) throw new Exception("ArrivalDate must be specified!");
		if($this->_nights<1) throw new Exception("Nights must be specified!");
		if(count($this->_rooms)<1) throw new Exception("At least one room must be specified!");
		$xml = "";
		$xml.= Helper::wrapTag("MaximumWaitTime",$this->getService()->getTimeout());
		$xml.= Helper::wrapTag("MaxResponses",$this->getService()->getMaxResult());
		if($this->getCity()>0) $xml.= Helper::wrapTag("CityCode",$this->getCity());
		if($this->getHotelCode()>0) $xml.= Helper::wrapTag('Hotels', Helper::wrapTag('HotelId',$this->getHotelCode()));
		$xml.= Helper::wrapTag("ArrivalDate",$this->getDateFrom());
		$xml.= Helper::wrapTag("Nights",intval($this->getNights()));
		$xml.= Helper::wrapTag('Apartments',$this->getApartments()?'true':'false');
		if($this->getSort()) $xml.= Helper::wrapTag("SortOrder",intval($this->getSort()));
		$rooms = '';
		foreach($this->getRooms() as $r) {
			$children = '';
			for($c=0; $c<$r['children']; $c++) $children.= Helper::wrapTag('ChildAge', Room::CHILD_AGE);
			$rooms.= Helper::wrapTag('Room',$children,['RoomCount'=>1,'Adults'=>$r['adults'],'CotCount'=>$r['infant']]);
		}
		$xml.= Helper::wrapTag('Rooms', $rooms);
		return $xml;
	}

	/**
	 * @return \Travelhood\Library\Provider\GoGlobal\Response\HotelSearch
	 */
	public function getResponse() {
		return parent::getResponse();
	}

    public function toArray()
    {
        return [
            'city' => $this->_city,
            'hotel_code' => $this->_hotelCode,
            'date_from' => $this->_dateFrom,
            'date_until' => $this->_dateUntil,
            'nights' => $this->_nights,
            'categories' => $this->_categories,
            'apartments' => $this->_apartments,
            'rooms' => $this->_rooms,
        ];
    }

}