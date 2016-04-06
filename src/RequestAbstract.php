<?php

namespace Travelhood\Library\Provider\GoGlobal;

abstract class RequestAbstract extends ServiceBase implements RequestInterface
{
	protected $_result = null;

	public function reset() {
		$this->_result = null;
		return $this;
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function getHeader() {
		$xml  = Helper::wrapTag('Agency', $this->getAgency());
		$xml .= Helper::wrapTag('User', $this->getUser());
		$xml .= Helper::wrapTag('Password', $this->getPassword());
		$xml .= Helper::wrapTag('Operation', static::getOperation());
		$xml .= Helper::wrapTag('OperationType', 'Request');
		return $xml;
	}

	/**
	 * @return string
	 */
	abstract public function getBody();

	/**
	 * @return ResponseInterface
	 */
	public function getResponse() {
		$name = str_replace('\Request\\','\Response\\',get_class($this));
		/** @var ResponseInterface $response */
		$response = new $name($this->getService());
		$response->setRequest($this);
		return $response;
	}

	public function getResult() {
		if(!$this->_result) {
			$log = $this->getService()->getLogger();
			$xml = $this->toString();
			if($log) $log->debug('BUILT: '.get_class($this).PHP_EOL.trim($xml).PHP_EOL);
            if($this->getService()->getCompress()) {
                $result = $this->getWSDL()->MakeRequestCompressed([
                    'requestType' => static::getRequestType(),
                    'xmlRequest' => $xml,
                ])->MakeRequestCompressedResult;
                $this->_result = gzdecode($result);
            } else {
                $this->_result = $this->getWSDL()->MakeRequest([
                    'requestType' => static::getRequestType(),
                    'xmlRequest' => $xml,
                ])->MakeRequestResult;
            }
            if($log) $log->debug('DONE: '.get_class($this).PHP_EOL.trim($this->_result).PHP_EOL);
            //dump(htmlentities($this->getWSDL()->__getLastRequest())); dump(htmlentities($this->toXml())); exit;
		}
		return $this->_result;
	}

	/**
	 * @return string|null
	 */
	public function toXml() {
		$xml =
			'<Root>'.
			'<Header>'.
			$this->getHeader().
			'</Header>'.
			'<Main>'.
			$this->getBody().
			'</Main>'.
			'</Root>';
		return $xml;
	}

	/**
	 * @return string|null
	 */
	public function toString() {
		return $this->toXml();
	}

	/**
	 * @return string|null
	 */
	public function __toString() {
		return $this->toString();
	}

	function toArray()
	{
		return [];
	}

}


