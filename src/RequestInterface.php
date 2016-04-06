<?php

namespace Travelhood\Library\Provider\GoGlobal;

interface RequestInterface {

	static function getOperation();
	static function getRequestType();

	function reset();
	function getHeader();
	function getBody();
	function getResult();
	function getResponse();
	function toXml();
	function toArray();
	function toString();
	function __toString();

}