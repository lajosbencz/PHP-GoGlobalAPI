<?php

namespace Travelhood\Library\Provider\GoGlobal;

abstract class ServiceBase {

	/** @var Service */
	protected $_service;

	/**
	 * @param Service|null $service (optional)
	 */
	public function __construct(Service $service=null) {
		$this->setService($service?:Service::getDefault());
	}

	/**
	 * @param Service $service
	 * @return $this
	 */
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

	/**
	 * @return SoapClient
	 */
	public function getWSDL() {
		return $this->getService()->getWSDL();
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->getService()->getUrl();
	}

	/**
	 * @return string
	 */
	public function getAgency() {
		return $this->getService()->getAgency();
	}

	/**
	 * @return string
	 */
	public function getUser() {
		return $this->getService()->getUser();
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->getService()->getPassword();
	}

}