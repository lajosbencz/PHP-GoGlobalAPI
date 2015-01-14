<?php

namespace LajosBencz\GoGlobal;

class Filter {

    protected static $ChildCountMax = 2;
    protected static $ChildAgeMin = 1;
    protected static $ChildAgeMax = 10;
    protected static $RoomCountMax = 10;
    protected static $RoomTypeMax = 3;
    protected static $ValidChildRooms = array(
        RoomType::DOUBLE,
        RoomType::TWIN,
    );

    protected static function WrapTag($tag, $value, $attributes=array()) {
        $attr = "";
        foreach($attributes as $k=>$v) $attr.= sprintf(" %s=\"%s\"",$k,$v);
        return sprintf("<%s%s>%s</%s>",$tag,$attr,$value,$tag);
    }

    protected
        $_api = null,
        $_usedTypes = array(),
        $_order = false,
        $_cityCode = 0,
        $_arrivalDate = 0,
        $_nights = 0,
        $_stars = array(),
        $_rooms = array();

    public function __construct(API $api) {
        $this->_api = &$api;
    }

    public function setOrder($order) {
        $this->_order = $order;
        return $this;
    }

    public function setCityCode($city) {
        $this->_cityCode = intval($city);
        return $this;
    }

    public function setArrivalDate($date) {
        $this->_arrivalDate = $date;
        return $this;
    }

    public function setNights($nights) {
        $this->_nights = $nights;
        return $this;
    }

    public function setStars($stars) {
        if(is_array($stars)) $this->_stars = $stars;
        else $this->_stars = func_get_args();
        return $this;
    }

    public function addRoom($type) {
        if(count($this->_rooms)>=self::$RoomCountMax) throw new \Exception("Max allowed rooms: ".self::$RoomCountMax);
        $this->_usedTypes[$type] = $type;
        if(count($this->_usedTypes)>self::$RoomTypeMax) throw new \Exception("Max allowed room types: ".self::$RoomTypeMax);
        $this->_rooms[] = array(
            'RoomType' => $type,
        );
        return $this;
    }

    protected function getLastRoomIndex() {
        $c = count($this->_rooms);
        return $c-1;
    }

    public function addChild($age) {
        if($age<self::$ChildAgeMin || $age>self::$ChildAgeMax) {
            throw new \Exception('Child age must be between '.self::$ChildAgeMin.' and '.self::$ChildAgeMax);
        }
        $i = $this->getLastRoomIndex();
        if($i<0) {
            throw new \Exception('No room added yet, cannot specify child!');
        }
        $rt = $this->_rooms[$i]['RoomType'];
        if(!in_array($rt,self::$ValidChildRooms)) {
            throw new \Exception('Can only add children to '.implode(', ',self::$ValidChildRooms).' room types!');
        }
        $c = count($this->_rooms[$i]['Children']);
        if($c>self::$ChildCountMax) {
            throw new \Exception('Maximum '.self::$ChildCountMax.' children allowed!');
        }
        if($c>0) {
            if($this->_rooms[$i]['Children'][0]<2) {
                throw new \Exception('Cannot add another child, the first is less than 2 years old!');
            }
        }
        $this->_rooms[$i]['Children'][] = $age;
        return $this;
    }

    public function getStars() {
        return $this->_stars;
    }

    public function getXml() {
        if($this->_cityCode<1) throw new \Exception(__CLASS__.": CityCode must be specified!");
        if($this->_arrivalDate<1) throw new \Exception(__CLASS__.": ArrivalDate must be specified!");
        if($this->_nights<1) throw new \Exception(__CLASS__.": Nights must be specified!");
        $xml = "";
        $xml.= self::WrapTag("MaximumWaitTime",$this->_api->getTimeout());
        $xml.= self::WrapTag("MaxResponses",$this->_api->getMaxResult());
        $xml.= self::WrapTag("CityCode",intval($this->_cityCode));
        $xml.= self::WrapTag("ArrivalDate",date("Y-m-d", strtotime($this->_arrivalDate)));
        $xml.= self::WrapTag("Nights",intval($this->_nights));
        //if($this->_order) $xml.= self::WrapTag("SortOrder",intval($this->_order));
        $reassigned = array();
        $rooms = array();
        foreach($this->_rooms as $r) {
            if(!in_array($r,$rooms,true)) {
                $rooms[] = $r;
                $r['RoomCount'] = 1;
                $reassigned[] = $r;
            }
            else {
                $k = array_search($r,$rooms,true);
                $reassigned[$k]['RoomCount']++;
            }
        }
        $rms = "";
        if(count($reassigned)>self::$RoomTypeMax) throw new \Exception("Max allowed room types: ".self::$RoomTypeMax);
        foreach($reassigned as $r) {
            $chi = '';
            $cot = 0;
            if(count($r['Children'])>0) {
                if(count($r['Children'])==1 && $r['Children'][0]==1) {
                    $cot = 1;
                } else {
                    foreach($r['Children'] as $c) {
                        $chi.= self::WrapTag('ChildAge',$c);
                    }
                }
            }
            $rms.= self::WrapTag("Room",$chi,array(
                'Type' => $r['RoomType'],
                'RoomCount' => $r['RoomCount'],
                'CotCount' => $cot,
            ));
        }
        $xml.= self::WrapTag('Rooms',$rms);
        return $xml;
    }

    public function __toString() {
        return $this->getXml();
    }

    public function toString() {
        return $this->getXml();
    }

}