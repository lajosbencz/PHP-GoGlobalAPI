<?php

namespace GoGlobal\Response;

use GoGlobal\Enum\Category as CategoryEnum;
use GoGlobal\Helper;
use GoGlobal\ResponseAbstract;
use GoGlobal\ResponseInterface;

class HotelInfoGeo extends ResponseAbstract implements ResponseInterface
{
	protected static $MAP_INFO = [
		'HotelSearchCode' => 'hotel_search_code',
		'HotelName' => 'hotel_name',
		'Address' => 'address',
		'CityCode' => 'city_id',
		'GeoCodes' => [
			'Latitude' => 'latitude',
			'Longitude' => 'longitude',
		],
		'Phone	' => 'phone',
		'Fax' => 'fax',
		'Category' => 'category',
		//'RoomCount' => 'room_count',
	];

	protected static $MAP_DESCRIPTION = [
		'Description' => 'layout',
		'HotelFacilities' => 'hotel',
		'RoomFacilities' => 'room',
	];

	protected function process() {

		$info = Helper::map(self::$MAP_INFO, $this->_xml);
		$info['descriptions'] = Helper::map(self::$MAP_DESCRIPTION, $this->_xml);
		$info['category'] = CategoryEnum::toFloat($info['category']);
		$info['images'] = [];
		foreach($this->_xml->Pictures->Picture as $picture) {
			$info['images'][] = (string)$picture;
		}
		$this->_data = $info;

	}
}