<?php

namespace Travelhood\Library\Provider\GoGlobal\Response;

use Travelhood\Library\Provider\GoGlobal\ResponseAbstract;
use Travelhood\Library\Provider\GoGlobal\ResponseInterface;

class HotelInfo extends HotelInfoGeo implements ResponseInterface
{
	protected static $MAP_INFO = [
		'HotelSearchCode' => 'hotel_search_code',
		'HotelName' => 'hotel_name',
		'Address' => 'address',
		'CityCode' => 'city_id',
		'Phone	' => 'phone',
		'Fax' => 'fax',
		'Category' => 'category',
		//'RoomCount' => 'room_count',
	];

}