<?php

namespace Travelhood\Library\Provider\GoGlobal\Response;

use SimpleXMLElement;
use Travelhood\Library\Provider\GoGlobal\Enum\Category as CategoryEnum;
use Travelhood\Library\Provider\GoGlobal\Helper;
use Travelhood\Library\Provider\GoGlobal\ResponseAbstract;
use Travelhood\Library\Provider\GoGlobal\ResponseInterface;

class HotelSearchGeo extends ResponseAbstract implements ResponseInterface
{

	protected static $MAP_HOTEL = [
		'HotelCode' => 'hotel_code',
		'HotelName' => 'hotel_name',
		'CountryId' => 'country_id',
		'Category' => 'category',
		'Location' => 'location',
		//'LocationCode' => 'location_code',
		'Thumbnail' => 'thumbnail',
		'GeoCodes' => [
			'Latitude' => 'latitude',
			'Longitude' => 'longitude',
		],
		'TripAdvisor' => [
			'Rating' => 'ta_rating',
			'RatingImage' => 'ta_rating_url',
			'ReviewCount' => 'ta_review_count',
			'Reviews' => 'ta_review_url',
		],
	];

	protected static $MAP_ROOM = [
		'HotelSearchCode' => 'hotel_search_code',
		'CxlDeadline' => 'date_cancel',
		'RoomType' => 'room_description',
		'RoomBasis' => 'board_id',
		//'Availability' => 'availability',
		'TotalPrice' => 'price',
		'Currency' => 'currency_id',
		//'Preferred' => 'preferred',
		'Remark' => 'comment',
		'SpecialOffer' => 'special_offer',
	];

	/**
	 * @return \Travelhood\Library\Provider\GoGlobal\Request\HotelSearchGeo
	 */
	public function getRequest() {
		return parent::getRequest();
	}

	protected function process() {
		$hotels = [];
		foreach($this->_xml->Hotel as $node) {
			$hotel = Helper::map(self::$MAP_HOTEL, $node);
			//$hotel['category'] = CategoryEnum::toFloat($hotel['category']);
			$hotel['city_id'] = $this->getRequest()->getCity();
			if(!array_key_exists($hotel['hotel_code'],$hotels)) {
				$hotels[$hotel['hotel_code']] = $hotel;
				$hotels[$hotel['hotel_code']]['rooms'] = [];
			}
			$room = Helper::map(self::$MAP_ROOM, $node);
			//$room['hotel_code'] = $hotel['hotel_code'];
			$room['date_cancel'] = Helper::fixCxlDate($room['date_cancel']);
			$hotels[$hotel['hotel_code']]['rooms'][] = $room;
		}
		$this->_data = $hotels;
	}
}