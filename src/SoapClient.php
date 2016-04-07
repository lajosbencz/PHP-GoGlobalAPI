<?php

namespace GoGlobal;

/**
 * Class SoapClient
 * @package GoGlobal
 * @method object MakeRequest(array $args)
 * @method object MakeRequestCompressed(array $args)
 */
class SoapClient extends \SoapClient
{
    protected $_service;

    public function __construct($wsdl, array $options)
    {
        parent::__construct($wsdl, $options);
    }

    public function setService(Service $service) {
        $this->_service = $service;
        return $this;
    }

    /**
     * @return Service
     */
    public function getService() {
        return $this->_service;
    }

    public function __doRequest($request, $location, $action, $version, $one_way=0) {
        $this->getService()->getLogger()->debug('SENT: '.$action.PHP_EOL.trim($request));
        $start = microtime(true);
        $result = parent::__doRequest($request, $location, $action, $version, $one_way);
        $this->getService()->getLogger()->debug('GOT: '.$action.' ['.strlen($result).'] '.sprintf("%0.4f",microtime(true)-$start).'s'.PHP_EOL.trim($result).PHP_EOL);
        return $result;
    }
}
