<?php

namespace LajosBencz\GoGlobal;

class ResultSet {

    private $_xmlRaw;
    private $_xml = null;
    private $_filter = null;
    private $_count = 0;
    private $_position = -1;

    public function __construct($xml=false, $filter=false) {
        if(is_string($xml)) {
            $this->fromString($xml);
        }
        if($filter!=false) {
            $this->setFilter($filter);
        }
    }

    public function fromString($xml) {
        $this->_xmlRaw = $xml;
        $this->_makeXml($this->_xmlRaw);
    }

    protected function _makeXml($string) {
        $this->_xml = new \SimpleXMLElement($string);
        $this->_count = count($this->_xml->Main->children());
        $this->reset();
    }

    public function setFilter(Filter $filter) {
        $this->_filter = $filter;
        $s = $filter->getStars();
        if(count($s)>0) {
            $i = -1;
            foreach($this->_xml->Main->children() as $hotel) {
                $i++;
                $c = intval($hotel->Category);
                if(!in_array($c,$s)) {
                    $this->_count--;
                } else {
                }
            }
            //$this->_count = count($this->_xml->Main->children());
            $this->reset();
        }
    }

    public function getCount() {
        return $this->_count;
    }

    public function getPosition() {
        return $this->_position;
    }

    public function seek($pos) {
        if($pos<0 || ($this->_count>0 && $pos>=$this->_count)) throw new \Exception("Invalid index given!");
        $this->_position = $pos;
    }

    public function reset() {
        $this->seek(0);
    }

    public function next() {
        if($this->_position >= $this->_count) return false;
        $d = $this->_rowToArray($this->_xml->Main->Hotel[$this->_position++]);
        $s = $this->_filter->getStars();
        if(count($s)>0 && !in_array($d['Category'],$s)) return $this->next();
        else return $d;
    }

    public function prev() {
        if($this->_position<0) return false;
        $d = $this->_rowToArray($this->_xml->Main[$this->_position--]);
        $s = $this->_filter->getStars();
        if(count($s) && !in_array($d['Category'],$s)) return $this->prev();
        else return $d;
    }

    protected function _rowToArray($ele) {
        if(!$ele) return false;
        $u = explode("/",(string)$ele->HotelSearchCode);
        $u = $u[0];
        return array(
            "HotelSearchCode" => (string)$ele->HotelSearchCode,
            "HotelUnique" => $u,
            "HotelCode" => (string)$ele->HotelCode,
            "HotelName" => (string)$ele->HotelName,
            "GG_Country_Id" => (string)$ele->CountryId,
            "CxlDeadline" => (string)$ele->CxlDeadline,
            "RoomType" => (string)$ele->RoomType,
            "RoomBasis" => (string)$ele->RoomBasis,
            "Availability" => (string)$ele->Availability,
            "TotalPrice" => (string)$ele->TotalPrice,
            "Currency" => (string)$ele->Currency,
            "Category" => max(1,intval((string)$ele->Category)),
            "Location" => (string)$ele->Location,
            "LocationCode" => (string)$ele->LocationCode,
            "Preferred" => (string)$ele->Preferred,
            "Thumbnail" => (string)$ele->Thumbnail,
            "Latitude" => (string)$ele->GeoCodes->Latitude,
            "Longitude" => (string)$ele->GeoCodes->Longitude,
            "Rating" => (string)$ele->TripAdvisor->Rating,
            "RatingImage" => (string)$ele->TripAdvisor->RatingImage,
            "ReviewCount" => (string)$ele->TripAdvisor->ReviewCount,
            "Reviews" => (string)$ele->TripAdvisor->Reviews,
        );
    }

}