<?php

namespace LajosBencz\GoGlobal;

class API {

    const URL = "http://xml.qa.goglobal.travel/XMLWebService.asmx";

    public static function StarsToBit($star) {
        $star = func_get_args();
        $b = 0;
        foreach($star as $s) {
            if(!is_int($s)) continue;
            $s--;
            if($s<1) continue;
            $b += (1<<$s);
        }
        return $b;
    }

    public static function BitHasStar($bit, $star) {
        return (($bit>>$star)&1)==1;
    }


    public static $CONFIG_FIELDS = array(
        'agency',
        'user',
        'password',
        'url'
    );

    protected $_agency;
    protected $_user;
    protected $_password;
    protected $_url;
    protected $_wsdl;
    protected $_maxResult = 10000;
    protected $_timeout = 60;

    public function __construct($config=array()) {
        foreach(array('agency','user','password') as $ck) if(!array_key_exists($ck,$config) || strlen($config[$ck])<1) throw new \InvalidArgumentException("Missing config value: [".$ck."]");
        $this->_agency = $config['agency'];
        $this->_user = $config['user'];
        $this->_password = $config['password'];
        if(array_key_exists('url',$config)) $this->_url = $config['url'];
        else $this->_url = self::URL;
        $this->_wsdl = new \SoapClient($this->_url."?WSDL");
    }

    public $LastXML = false;

    public function setMaxResult($max) {
        $this->_maxResult = intval($max);
    }
    public function getMaxResult() {
        return $this->_maxResult;
    }

    public function setTimeout($timeout) {
        $this->_timeout = intval($timeout);
    }
    public function getTimeout() {
        return $this->_timeout;
    }

    public function createFilter() {
        return new Filter($this);
    }

    protected function _makeXmlHeader($requestType) {
        $ts = RequestType::getString($requestType);
        if(substr($ts,-4,4)=="_GEO") $ts = substr($ts,0,-4);
        return "
<Header>
    <Agency>".$this->_agency."</Agency>
    <User>".$this->_user."</User>
    <Password>".$this->_password."</Password>
    <Operation>".$ts."_REQUEST</Operation>
    <OperationType>Request</OperationType>
</Header>";
    }

    protected function _sendRequest($requestType, $xmlRequest) {
        $xmlRequest = str_replace('><',">\r\n\t<",$xmlRequest);
        $xml = "<Root>".$xmlRequest."</Root>";
        $resp = $this->_wsdl->MakeRequest(array(
            'requestType' => $requestType,
            'xmlRequest' => $xml,
        ));
        $this->LastXML = $xml;
        $mrr = $resp->MakeRequestResult;
        return $mrr;
    }

    protected function _parseInfo($xmlString) {
        //print "<textarea rows=40 cols=60>".format_xml($xmlString)."</textarea>";
        $xml = new \SimpleXMLElement($xmlString);
        $d = array(
            'HotelCode' => (string)$xml->Main->HotelSearchCode,
            'HotelName' => (string)$xml->Main->HotelName,
            'Address' => (string)$xml->Main->Address,
            'CityCode' => (string)$xml->Main->CityCode,
            'Phone' => (string)$xml->Main->Phone,
            'Fax' => (string)$xml->Main->Fax,
            'Category' => (string)$xml->Main->Category,
            'Description' => (string)$xml->Main->Description,
            'HotelFacilities' => (string)$xml->Main->HotelFacilities,
            'RoomFacilities' => (string)$xml->Main->RoomFacilities,
            'RoomCount' => (string)$xml->Main->RoomCount,
            'Pictures' => array(),
        );
        $pics = $xml->Main->Pictures->children()->Picture;
        if(count($pics)>0) {
            foreach($pics as $p) {
                $d['Pictures'][] = (string)$p;
            }
        }
        if(strlen((string)$xml->Main->GeoCodes)>0) {
            $d['Latitude'] = (string)$xml->Main->GeoCodes->Latitude;
            $d['Longitude'] = (string)$xml->Main->GeoCodes->Longitude;
        }
        if(count($xml->Main->TripAdvisor->children())==4) {
            $d['Rating'] = (string)$xml->Main->TripAdvisor->Rating;
            $d['RatingImage'] = (string)$xml->Main->TripAdvisor->RatingImage;
            $d['Reviews'] = (string)$xml->Main->TripAdvisor->Reviews;
            $d['ReviewCount'] = (string)$xml->Main->TripAdvisor->ReviewCount;
        }
        return $d;
    }

    public function getHotelInfo($hotelCode) {
        $rt = RequestType::HOTEL_INFO;
        $xml = "
<Main>
    <HotelSearchCode>".$hotelCode."</HotelSearchCode>
</Main>";
        return $this->_parseInfo($this->_sendRequest($rt, $this->_makeXmlHeader($rt).$xml));
    }

    public function getHotelInfoGeo($hotelCode) {
        $rt = RequestType::HOTEL_INFO_GEO;
        $xml = "
<Main>
    <HotelSearchCode>".$hotelCode."</HotelSearchCode>
</Main>";
        return $this->_parseInfo($this->_sendRequest($rt, $this->_makeXmlHeader($rt).$xml));
    }

    public function getHotelSearch(Filter $filter) {
        $rt = RequestType::HOTEL_SEARCH;
        $xml = "
<Main>
    ".$filter->getXml()."
</Main>";
        return new ResultSet($this->_sendRequest($rt, $this->_makeXmlHeader($rt).$xml), $filter);
    }
    public function getHotelSearchGeo(Filter $filter) {
        $rt = RequestType::HOTEL_SEARCH_GEO;
        $xml = "
<Main>
    ".$filter->getXml()."
</Main>";
        return new ResultSet($this->_sendRequest($rt, $this->_makeXmlHeader($rt).$xml), $filter);
    }

}