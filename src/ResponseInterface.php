<?php

namespace Travelhood\Library\Provider\GoGlobal;

interface ResponseInterface {

	function setRequest(RequestInterface $request);
	function getRequest();
	//function process();
	function getData();
	function __toString();
	function toString();
	function toArray();

}