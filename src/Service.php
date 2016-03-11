<?php

namespace Travelhood\Library\Provider\GoGlobal;

use InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;
use Travelhood\Library\Provider\GoGlobal\Enum\Request as RequestEnum;
use Travelhood\Library\Provider\GoGlobal\Request\BookingCancel;
use Travelhood\Library\Provider\GoGlobal\Request\BookingInsert;
use Travelhood\Library\Provider\GoGlobal\Request\HotelInfo;
use Travelhood\Library\Provider\GoGlobal\Request\HotelInfoGeo;
use Travelhood\Library\Provider\GoGlobal\Request\HotelSearch;
use Travelhood\Library\Provider\GoGlobal\Request\HotelSearchGeo;

class Service implements LoggerAwareInterface
{
    const URL_SERVICE = "http://xml.qa.goglobal.travel/XMLWebService.asmx";

    private static $_default = null;
    public static function getDefault() {
        return self::$_default;
    }
    public static function setDefault(Service $service) {
        self::$_default = $service;
    }

    protected $_agency;
    protected $_user;
    protected $_password;
    protected $_url;
    protected $_wsdl;
    protected $_maxResult = 10000;
    protected $_timeout = 60;
    protected $_logger;


    public function __construct($config = array()) {
        foreach(array('agency', 'user', 'password') as $ck) {
            if(!array_key_exists($ck, $config) || strlen($config[$ck]) < 1) {
                throw new InvalidArgumentException("Missing config value: [".$ck."]");
            }
        }
        $this->_agency 		= $config['agency'];
        $this->_user 		= $config['user'];
        $this->_password 	= $config['password'];
        if(array_key_exists('url', $config)) {
            $this->_url 	= $config['url'];
        } else {
            $this->_url 	= self::URL_SERVICE;
        }
        $this->_wsdl = new SoapClient($this->_url."?WSDL", ['trace'=>1,'connection_timeout'=>30000,'exceptions'=>1]);
        $this->_wsdl->setService($this);
        if(!self::$_default) {
            self::setDefault($this);
        }
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger() {
        return $this->_logger;
    }

    public function getWSDL() {
        return $this->_wsdl;
    }

    public function getUrl() {
        return $this->_url;
    }

    public function getAgency() {
        return $this->_agency;
    }

    public function getUser() {
        return $this->_user;
    }

    public function getPassword() {
        return $this->_password;
    }

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

    public function hotelSearch() {
        return (new HotelSearch($this));//->getResponse()->getData();
    }

    public function hotelSearchGeo() {
        return (new HotelSearchGeo($this));//'->getResponse()->getData();
    }

    public function hotelInfoData($hotelSearchCode) {
        return (new HotelInfo($this))->setHotelSearchCode($hotelSearchCode)->getResponse()->getData();
    }

    public function hotelInfoGeoData($hotelSearchCode) {
        $request = new HotelInfoGeo($this);
        $request->setHotelSearchCode($hotelSearchCode);
        $data = $request->getResponse()->getData();
        return $data;
    }

    public function bookingInsert() {
        return (new BookingInsert($this));
    }

    /**
     * @param string $bookingCode
     * @return BookingCancel
     */
    public function bookingCancel($bookingCode) {
        return (new BookingCancel($this))->setBookingCode($bookingCode);
    }

}
